<?php

namespace App\Http\Controllers\Backend\Payments;

use App\Http\Controllers\Backend\Payments\Duitku\DuitkuController;
use App\Http\Controllers\Backend\Payments\Flutterwave\FlutterwaveController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Payments\IyZico\IyZicoController;
use App\Http\Controllers\Backend\Payments\Paypal\PaypalController;
use App\Http\Controllers\Backend\Payments\Paystack\PaystackController;
use App\Http\Controllers\Backend\Payments\Stripe\StripePaymentController;
use App\Http\Controllers\Backend\Payments\Paytm\PaytmPaymentController;
use App\Http\Controllers\Backend\Payments\Razorpay\RazorpayController;
use App\Http\Controllers\Backend\Payments\Yookassa\YookassaPaymentController;
use App\Models\AffiliateEarning;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPackage;
use App\Models\User;

class PaymentsController extends Controller
{
    # init payment gateway
    public function initPayment()
    {
        $payment_method = session('payment_method');
        if ($payment_method == 'paypal') {
            return (new PaypalController())->initPayment();
        } else if ($payment_method == 'stripe') {
            return (new StripePaymentController())->initPayment();
        } else if ($payment_method == 'paytm') {
            return (new PaytmPaymentController())->initPayment();
        } else if ($payment_method == 'razorpay') {
            return (new RazorpayController())->initPayment();
        } else if ($payment_method == 'iyzico') {
            return (new IyZicoController)->initPayment();
        } else if ($payment_method == 'paystack') {
            return (new PaystackController)->initPayment();
        } else if ($payment_method == 'flutterwave') {
            return (new FlutterwaveController)->initPayment();
        } else if ($payment_method == 'duitku') {
            return (new DuitkuController)->initPayment();
        } else if ($payment_method == 'yookassa') {
            return (new YookassaPaymentController)->initPayment();
        }
        # todo::[update versions] more gateways
        return $this->payment_success();
    }

    # payment successful
    public function payment_success(
        $payment_details = null,
        $user_ = null,
        $package_id_ = null,
        $amount_ = null,
        $payment_method_ = null
    ) {
        $user = $user_ ?? auth()->user();
        $package_id = $package_id_ ?? session('package_id');
        $amount = $amount_ ?? session('amount');
        $payment_method = $payment_method_ ?? session('payment_method');

        // update subscription package & others 
        $package = SubscriptionPackage::where('id', $package_id)->first();

        # subscription history
        $subscriptionHistory = new SubscriptionHistory;
        $subscriptionHistory->user_id = $user->id;
        $subscriptionHistory->old_subscription_package_id = $user->subscription_package_id;
        $subscriptionHistory->subscription_package_id = $package->id;

        $subscriptionHistory->old_word_balance = $user->this_month_available_words;
        $subscriptionHistory->new_word_balance = $package->total_words_per_month;
        $subscriptionHistory->total_word_balance = $user->this_month_available_words + $package->total_words_per_month;

        $subscriptionHistory->old_image_balance = $user->this_month_available_images;
        $subscriptionHistory->new_image_balance = $package->total_images_per_month;
        $subscriptionHistory->total_image_balance = $user->this_month_available_images + $package->total_images_per_month;

        $subscriptionHistory->old_s2t_balance = $user->this_month_available_s2t;
        $subscriptionHistory->new_s2t_balance = $package->total_speech_to_text_per_month;
        $subscriptionHistory->total_s2t_balance = $user->this_month_available_s2t + $package->total_speech_to_text_per_month;

        $subscriptionHistory->price = $amount;
        $subscriptionHistory->payment_method = $payment_method;
        $subscriptionHistory->payment_details = !is_null($payment_details) ? json_encode($payment_details) : null;
        $subscriptionHistory->save();

        // check affiliate & calculate commissions
        if (getSetting('enable_affiliate_system') == '1') {
            if (!is_null($user->referred_by)) {

                $giveCommission = false;
                if (getSetting('enable_affiliate_continuous_commission') == "1") {
                    $giveCommission = true;
                    $user->is_commission_calculated = 0;
                } else if ($user->is_commission_calculated == 0) {
                    $giveCommission = true;
                }

                if ($giveCommission) {
                    $referredBy = User::where('id', $user->referred_by)->first();
                    if (!is_null($referredBy)) {
                        $earning = new AffiliateEarning;
                        $earning->user_id = $user->id;
                        $earning->referred_by = $referredBy->id;
                        $earning->subscription_history_id = $subscriptionHistory->id;
                        $earning->amount = ((float) $subscriptionHistory->price * (float) getSetting('affiliate_commission')) / 100;
                        $earning->commission_rate = getSetting('affiliate_commission');
                        $earning->save();

                        $referredBy->user_balance += (float) $earning->amount;
                        $referredBy->save();
                    }
                }
            }
        }

        # user
        $user->subscription_package_id = $package->id;
        $user->this_month_used_words = 0;
        $user->this_month_available_words += (int) $package->total_words_per_month;

        $user->this_month_used_images = 0;
        $user->this_month_available_images += (int) $package->total_images_per_month;

        $user->this_month_used_s2t = 0;
        $user->this_month_available_s2t += (int) $package->total_speech_to_text_per_month;
        $user->save();

        clearPaymentSession();
        flash(localize('Subscription package updated successfully'))->success();
        return redirect()->route('writebot.dashboard');
    }

    # payment failed
    public function payment_failed()
    {
        clearPaymentSession();
        flash(localize('Payment failed, please try again'))->error();
        return redirect()->route('subscriptions.index');
    }
}
