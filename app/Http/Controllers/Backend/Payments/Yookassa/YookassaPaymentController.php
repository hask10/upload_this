<?php

namespace App\Http\Controllers\Backend\Payments\Yookassa;

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\NotificationEventType;
use App\Http\Controllers\Backend\Payments\PaymentsController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use YooKassa\Model\Notification\NotificationFactory;

class YookassaPaymentController extends Controller
{
    public function initPayment()
    {
        try {

            $client = $this->_getAuthClient();

            $amount = $this->_calculateAmount();
            $currency = $this->_getCurrency();

            $idempotenceKey = uniqid('', true);

            $response = $client->createPayment(
                [
                    'amount' => [
                        'value' => $amount,
                        'currency' => $currency,
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('youkassa.finish'),
                    ],
                    'metadata' => [
                        'user_id' => auth()->id(),
                        'package_id' => session('package_id'),
                        'amount' => $amount
                    ],
                    'capture' => true
                ],
                $idempotenceKey
            );

            session()->put('yookassa_payment_id', $response->id);

            return Redirect::to($response->getConfirmation()->getConfirmationUrl());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Failed payment yookassa');
            dd($e);
            return (new PaymentsController)->payment_failed();
        }
    }

    public function process(Request $request)
    {
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        try {
            if ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED) {
                $notification = new NotificationSucceeded($requestBody);
                $payment = $notification->getObject();
                $metadata = $payment->getMetadata();

                $user = User::find($metadata['user_id']);
                $package_id = $metadata['package_id'];
                $amount = $metadata['amount'];

                $payment_id = $payment->getId();
                \Illuminate\Support\Facades\Log::info("Yookassa payment id: $payment_id");

                (new PaymentsController)->payment_success(
                    json_encode(["status" => "Success"]),
                    $user,
                    $package_id,
                    $amount,
                    'yookassa'
                );
            }
        } catch (Exception $e) {
            // todo	
        }

        return response()->json(['message' => 'Success'], 200);
    }

    public function finish(Request $request)
    {
        $client = $this->_getAuthClient();
        $payment = $client->getPaymentInfo(session('yookassa_payment_id'));

        if ($payment->getStatus() == 'succeeded') {
            clearPaymentSession();
            flash(localize('Subscription package updated successfully'))->success();
            return redirect()->route('writebot.dashboard');
        } else {
            return (new PaymentsController)->payment_failed();
        }
    }

    private function _getAuthClient()
    {
        $shopId = env('YOOKASSA_SHOP_ID');
        $secretKey = env('YOOKASSA_SECRET_KEY');

        $client = new \YooKassa\Client();
        $client->setAuth($shopId, $secretKey);

        return $client;
    }

    private function _getCurrency()
    {
        switch (env('YOOKASSA_CURRENCY_CODE')) {
            case 'rub':
                return \YooKassa\Model\CurrencyCode::RUB;
            case 'usd':
                return \YooKassa\Model\CurrencyCode::USD;
            default:
                // usd as a deafault currency
                return \YooKassa\Model\CurrencyCode::USD;
        }
    }

    private function _calculateAmount()
    {
        $amount = session('amount');
        $amount = formatPrice($amount, false, false, false);
        $amount = str_replace(",", "", $amount);

        return $amount;
    }
}
