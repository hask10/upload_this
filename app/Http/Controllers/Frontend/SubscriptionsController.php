<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Backend\Payments\PaymentsController;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    # subscribe
    public function subscribe(Request $request)
    {
        $package = SubscriptionPackage::where('id', $request->package_id)->first(['price']);

        $request->session()->put('package_id', $request->package_id);
        $request->session()->put('amount', $package->price);
        $request->session()->put('payment_method', $request->payment_method);

        # init payment
        $payment = new PaymentsController;
        return $payment->initPayment();
    }
}
