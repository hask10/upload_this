<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPackage;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    # registration form validation
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    # make new registration here
    protected function create(array $data)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create($data);
            return $user;
        }
        return null;
    }

    # register new customer here
    public function register(Request $request)
    {

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->first() != null) {
                flash(localize('Email or Phone already exists.'))->error();
                return back()->withInput();
            }
        }

        if ($request->phone != null) {
            if (User::where('phone', $request->phone)->first() != null) {
                flash(localize('An user already exists with this phone number.'))->error();
                return back()->withInput();
            }
        }

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                flash(localize($error))->error();
            }
            return back()->withInput();
        }

        # handle referral_code  
        $data = [
            'name' => $request->name,
            'email' =>  $request->email,
            'phone' => validatePhone($request->phone),
            'password' => Hash::make($request->password),
        ];

        if (getSetting('enable_affiliate_system') == '1') {
            $referral_code = isset($_COOKIE['referral_code']) ? $_COOKIE['referral_code'] : null;
            if (!is_null($referral_code)) {
                $referredByUser = User::where('referral_code', $referral_code)->first();
                if (!is_null($referredByUser)) {
                    $data['referred_by'] = $referredByUser->id;
                }
            }
        }
        $user = $this->create($data);

        if ($user) {
            $this->guard()->login($user);
        }

        # verification
        if (getSetting('registration_verification_with') == "disable") {
            $user->email_or_otp_verified = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();
            flash(localize('Registration successful.'))->success();
        } else {
            if (getSetting('registration_verification_with') == 'email') {
                try {
                    $user->sendVerificationNotification();
                    flash(localize('Registration successful. Please verify your email.'))->success();
                } catch (\Throwable $th) {
                    $user->delete();
                    flash(localize('Registration failed. Please try again later.'))->error();
                }
            }
            // else being handled in verification controller
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    # action after registration
    protected function registered(Request $request, $user)
    {
        // subscription
        $starter = SubscriptionPackage::isActive()
            ->where('id', 1)
            ->first();

        if (!is_null($starter)) {
            if (getSetting('enable_affiliate_system') == '1') {
                $user->is_commission_calculated = 0; # referral user will get commission of paid subscription later
            }

            $user->subscription_package_id      = $starter->id;
            $user->this_month_available_words   = (int) $starter->total_words_per_month;
            $user->this_month_available_images  = (int) $starter->total_images_per_month;
            $user->this_month_available_s2t     = (int) $starter->total_speech_to_text_per_month;
            $user->save();

            $subscriptionHistory = new SubscriptionHistory;
            $subscriptionHistory->user_id = $user->id;
            $subscriptionHistory->subscription_package_id = $starter->id;
            $subscriptionHistory->new_word_balance = $starter->total_words_per_month;
            $subscriptionHistory->new_image_balance = $starter->total_images_per_month;
            $subscriptionHistory->new_s2t_balance = $starter->total_speech_to_text_per_month;
            $subscriptionHistory->save();
        }

        // redirect
        if ($user->email_or_otp_verified == 0) {
            if (getSetting('registration_verification_with') == 'email') {
                return redirect()->route('verification.notice');
            } else {
                return redirect()->route('verification.phone');
            }
        } elseif (session('link') != null) {
            $link = session('link');
            session()->forget('link');
            return redirect($link);
        } else {
            return redirect()->route('writebot.dashboard');
        }
    }
}
