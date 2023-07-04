<?php

namespace App\Http\Controllers\Backend\Payments\Duitku;

use App\Http\Controllers\Backend\Payments\PaymentsController;
use Royryando\Duitku\Http\Controllers\DuitkuBaseController;
use Illuminate\Http\Request;
use Royryando\Duitku\Enums\DuitkuCallbackCode;
use Illuminate\Support\Facades\Redirect;
use Royryando\Duitku\Facades\Duitku;

class DuitkuController extends DuitkuBaseController
{
    # duitku payment view
    public function initPayment()
    {
        $user = auth()->user();
        $amount = session('amount');
        $methods = Duitku::paymentMethods((float)$amount);
        return view('payments.duitku', compact('methods'));
    }

    # duitku payment
    public function pay(Request $request)
    {
        $amount = session('amount');
        $method = $request->input('payment_method');
        $user = auth()->user();
        /*
         * Create invoice
         */
        $response = Duitku::createInvoice('subscription-' . time(), (float) $amount, $method, 'Subscription package', $user->name, $user->email, 30);
        if (!$response['success']) {
            abort(400, $response['message']);
        }
        /*
         * Redirect to the payment url
         */
        return Redirect::to($response['payment_url']);
    }

    # callback
    protected function paymentCallback(Request $request)
    {
        try {
            $merchantCode = config('duitku.merchant_code');
            $apiKey = config('duitku.api_key');
            $amount = $request->input('amount');
            $merchantOrderId = $request->input('merchantOrderId');
            $resultCode = $request->input('resultCode');
            $signature = $request->input('signature');

            if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
                $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
                $calcSignature = md5($params);

                if ($signature == $calcSignature) {
                    if ($resultCode == DuitkuCallbackCode::SUCCESS) {
                        // Payment success
                        $this->success();
                    } else {
                        // Payment failed or expired
                        $this->failed();
                    }
                } else {
                    // Bad signature 
                    $this->failed();
                }
            } else {
                // FAILED
                $this->failed();
            }
        } catch (\Exception $ex) {
            $this->failed();
        }
    }
    # success
    protected function success()
    {
        try {
            $payment = ["status" => "Success"];
            return (new PaymentsController)->payment_success(json_encode($payment));
        } catch (\Exception $e) {
            return (new PaymentsController)->payment_failed();
        }
    }

    # failed
    protected function failed()
    {
        return (new PaymentsController)->payment_failed();
    }

    # return
    public function myReturnCallback()
    {
        return (new PaymentsController)->payment_failed();
    }
}
