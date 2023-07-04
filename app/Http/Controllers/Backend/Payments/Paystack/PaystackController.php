<?php

namespace App\Http\Controllers\Backend\Payments\Paystack;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Payments\PaymentsController;
use Illuminate\Http\Request;
use Paystack;


class PaystackController extends Controller
{
    # init payment
    public function initPayment()
    {
        $user = auth()->user();
        $amount = session('amount');

        $request            = new Request;
        $request->email     = $user->email;
        $request->amount    = round($amount  * 100);

        $request->currency  = env('PAYSTACK_CURRENCY_CODE', 'USD');
        $request->reference = Paystack::genTranxRef();
        return Paystack::getAuthorizationUrl($request)->redirectNow();
    }

    # callback  
    public function callback()
    {
        $payment = Paystack::getPaymentData();

        $payment_detalis = json_encode($payment);
        if (!empty($payment['data']) && $payment['data']['status'] == 'success') {
            return (new PaymentsController)->payment_success(json_encode($payment_detalis));
        } else {
            return (new PaymentsController)->payment_failed();
        }
    }
}
