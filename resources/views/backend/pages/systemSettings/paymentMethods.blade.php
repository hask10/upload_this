@extends('backend.layouts.master')

@section('title')
    {{ localize('Payment Methods Settings') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">


            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('Payment Methods Settings') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ localize('Payment Methods Settings') }}</li>
                                </ol>
                            </div>
                            <div class="tt-action">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.settings.updatePaymentMethods') }}" method="POST"
                        enctype="multipart/form-data" class="pb-650">
                        @csrf

                        <!--paypal settings-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Paypal Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="paypal">
                                <div class="mb-3">
                                    <label for="PAYPAL_CLIENT_ID"
                                        class="form-label">{{ localize('Paypal Client ID') }}</label>
                                    <input type="hidden" name="types[]" value="PAYPAL_CLIENT_ID">
                                    <input type="text" id="PAYPAL_CLIENT_ID" name="PAYPAL_CLIENT_ID" class="form-control"
                                        value="{{ env('PAYPAL_CLIENT_ID') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="PAYPAL_CLIENT_SECRET"
                                        class="form-label">{{ localize('Paypal Client Secret') }}</label>
                                    <input type="hidden" name="types[]" value="PAYPAL_CLIENT_SECRET">
                                    <input type="text" id="PAYPAL_CLIENT_SECRET" name="PAYPAL_CLIENT_SECRET"
                                        class="form-control" value="{{ env('PAYPAL_CLIENT_SECRET') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Paypal') }}</label>
                                    <select id="enable_paypal" class="form-control select2" name="enable_paypal"
                                        data-toggle="select2">
                                        <option value="0" {{ getSetting('enable_paypal') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1" {{ getSetting('enable_paypal') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Test Sandbox Mode') }}</label>
                                    <select id="paypal_sandbox" class="form-control select2" name="paypal_sandbox"
                                        data-toggle="select2">
                                        <option value="0" {{ getSetting('paypal_sandbox') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1" {{ getSetting('paypal_sandbox') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--paypal settings-->


                        <!--stripe settings-->
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Stripe Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="stripe">
                                <div class="mb-3">
                                    <label for="STRIPE_KEY" class="form-label">{{ localize('Stripe Key') }}</label>
                                    <input type="hidden" name="types[]" value="STRIPE_KEY">
                                    <input type="text" id="STRIPE_KEY" name="STRIPE_KEY" class="form-control"
                                        value="{{ env('STRIPE_KEY') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="STRIPE_SECRET" class="form-label">{{ localize('Stripe Secret') }}</label>
                                    <input type="hidden" name="types[]" value="STRIPE_SECRET">
                                    <input type="text" id="STRIPE_SECRET" name="STRIPE_SECRET" class="form-control"
                                        value="{{ env('STRIPE_SECRET') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Stripe') }}</label>
                                    <select id="enable_stripe" class="form-control select2" name="enable_stripe"
                                        data-toggle="select2">
                                        <option value="0" {{ getSetting('enable_stripe') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1" {{ getSetting('enable_stripe') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--stripe settings-->

                        <!--paytm settings-->
                        <div class="card mb-4" id="section-4">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('PayTm Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="paytm">
                                <div class="mb-3">
                                    <label for="PAYTM_ENVIRONMENT"
                                        class="form-label">{{ localize('PayTm Environment') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_ENVIRONMENT">
                                    <input type="text" id="PAYTM_ENVIRONMENT" name="PAYTM_ENVIRONMENT"
                                        class="form-control" value="{{ env('PAYTM_ENVIRONMENT') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYTM_MERCHANT_ID"
                                        class="form-label">{{ localize('PayTm Merchant ID') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_MERCHANT_ID">
                                    <input type="text" id="PAYTM_MERCHANT_ID" name="PAYTM_MERCHANT_ID"
                                        class="form-control" value="{{ env('PAYTM_MERCHANT_ID') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYTM_MERCHANT_KEY"
                                        class="form-label">{{ localize('PayTm Merchant Key') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_MERCHANT_KEY">
                                    <input type="text" id="PAYTM_MERCHANT_KEY" name="PAYTM_MERCHANT_KEY"
                                        class="form-control" value="{{ env('PAYTM_MERCHANT_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYTM_MERCHANT_WEBSITE"
                                        class="form-label">{{ localize('PayTm Merchant Website') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_MERCHANT_WEBSITE">
                                    <input type="text" id="PAYTM_MERCHANT_WEBSITE" name="PAYTM_MERCHANT_WEBSITE"
                                        class="form-control" value="{{ env('PAYTM_MERCHANT_WEBSITE') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYTM_CHANNEL" class="form-label">{{ localize('PayTm Channel') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_CHANNEL">
                                    <input type="text" id="PAYTM_CHANNEL" name="PAYTM_CHANNEL" class="form-control"
                                        value="{{ env('PAYTM_CHANNEL') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYTM_INDUSTRY_TYPE"
                                        class="form-label">{{ localize('PayTm Industry Type') }}</label>
                                    <input type="hidden" name="types[]" value="PAYTM_INDUSTRY_TYPE">
                                    <input type="text" id="PAYTM_INDUSTRY_TYPE" name="PAYTM_INDUSTRY_TYPE"
                                        class="form-control" value="{{ env('PAYTM_INDUSTRY_TYPE') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable PayTm') }}</label>
                                    <select id="enable_paytm" class="form-control select2" name="enable_paytm"
                                        data-toggle="select2">
                                        <option value="0" {{ getSetting('enable_paytm') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1" {{ getSetting('enable_paytm') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--paytm settings-->


                        <!--razorpay settings-->
                        <div class="card mb-4" id="section-5">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Razorpay Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="razorpay">
                                <div class="mb-3">
                                    <label for="RAZORPAY_KEY" class="form-label">{{ localize('Razorpay Key') }}</label>
                                    <input type="hidden" name="types[]" value="RAZORPAY_KEY">
                                    <input type="text" id="RAZORPAY_KEY" name="RAZORPAY_KEY" class="form-control"
                                        value="{{ env('RAZORPAY_KEY') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="RAZORPAY_SECRET"
                                        class="form-label">{{ localize('Razorpay Secret') }}</label>
                                    <input type="hidden" name="types[]" value="RAZORPAY_SECRET">
                                    <input type="text" id="RAZORPAY_SECRET" name="RAZORPAY_SECRET"
                                        class="form-control" value="{{ env('RAZORPAY_SECRET') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Razorpay') }}</label>
                                    <select id="enable_razorpay" class="form-control select2" name="enable_razorpay"
                                        data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('enable_razorpay') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('enable_razorpay') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--razorpay settings-->

                        <!--iyzico settings-->
                        <div class="card mb-4" id="section-6">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('IyZico Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="iyzico">
                                <div class="mb-3">
                                    <label for="IYZICO_API_KEY"
                                        class="form-label">{{ localize('IyZico API Key') }}</label>
                                    <input type="hidden" name="types[]" value="IYZICO_API_KEY">
                                    <input type="text" id="IYZICO_API_KEY" name="IYZICO_API_KEY" class="form-control"
                                        value="{{ env('IYZICO_API_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="IYZICO_SECRET_KEY"
                                        class="form-label">{{ localize('IyZico Secret Key') }}</label>
                                    <input type="hidden" name="types[]" value="IYZICO_SECRET_KEY">
                                    <input type="text" id="IYZICO_SECRET_KEY" name="IYZICO_SECRET_KEY"
                                        class="form-control" value="{{ env('IYZICO_SECRET_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable IyZico') }}</label>
                                    <select id="enable_iyzico" class="form-control select2" name="enable_iyzico"
                                        data-toggle="select2">
                                        <option value="0" {{ getSetting('enable_iyzico') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1" {{ getSetting('enable_iyzico') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Test Sandbox Mode') }}</label>
                                    <select id="iyzico_sandbox" class="form-control select2" name="iyzico_sandbox"
                                        data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('iyzico_sandbox') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('iyzico_sandbox') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--iyzico settings-->


                        <!-- paystack settings-->
                        <div class="card mb-4" id="section-7">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Paystack Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="paystack">

                                <div class="mb-3">
                                    <label for="PAYSTACK_PUBLIC_KEY"
                                        class="form-label">{{ localize('Paystack Public Key') }}</label>
                                    <input type="hidden" name="types[]" value="PAYSTACK_PUBLIC_KEY">
                                    <input type="text" id="PAYSTACK_PUBLIC_KEY" name="PAYSTACK_PUBLIC_KEY"
                                        class="form-control" value="{{ env('PAYSTACK_PUBLIC_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYSTACK_SECRET_KEY"
                                        class="form-label">{{ localize('Secret Key') }}</label>
                                    <input type="hidden" name="types[]" value="PAYSTACK_SECRET_KEY">
                                    <input type="text" id="PAYSTACK_SECRET_KEY" name="PAYSTACK_SECRET_KEY"
                                        class="form-control" value="{{ env('PAYSTACK_SECRET_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="MERCHANT_EMAIL"
                                        class="form-label">{{ localize('Merchant Email') }}</label>
                                    <input type="hidden" name="types[]" value="MERCHANT_EMAIL">
                                    <input type="text" id="MERCHANT_EMAIL" name="MERCHANT_EMAIL" class="form-control"
                                        value="{{ env('MERCHANT_EMAIL') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="PAYSTACK_CURRENCY_CODE"
                                        class="form-label">{{ localize('Paystack Currency Code') }}</label>
                                    <input type="hidden" name="types[]" value="PAYSTACK_CURRENCY_CODE">
                                    <input type="text" id="PAYSTACK_CURRENCY_CODE" name="PAYSTACK_CURRENCY_CODE"
                                        class="form-control" value="{{ env('PAYSTACK_CURRENCY_CODE') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Paystack') }}</label>
                                    <select id="enable_paystack" class="form-control select2" name="enable_paystack"
                                        data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('enable_paystack') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('enable_paystack') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- paystack settings -->


                        <!-- flutterwave settings-->
                        <div class="card mb-4" id="section-8">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Flutterwave Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="flutterwave">

                                <div class="mb-3">
                                    <label for="FLW_PUBLIC_KEY"
                                        class="form-label">{{ localize('Flutterwave Public Key') }}</label>
                                    <input type="hidden" name="types[]" value="FLW_PUBLIC_KEY">
                                    <input type="text" id="FLW_PUBLIC_KEY" name="FLW_PUBLIC_KEY" class="form-control"
                                        value="{{ env('FLW_PUBLIC_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="FLW_SECRET_KEY"
                                        class="form-label">{{ localize('Flutterwave Secret Key') }}</label>
                                    <input type="hidden" name="types[]" value="FLW_SECRET_KEY">
                                    <input type="text" id="FLW_SECRET_KEY" name="FLW_SECRET_KEY" class="form-control"
                                        value="{{ env('FLW_SECRET_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="FLW_SECRET_HASH"
                                        class="form-label">{{ localize('Flutterwave Secret Hash') }}</label>
                                    <input type="hidden" name="types[]" value="FLW_SECRET_HASH">
                                    <input type="text" id="FLW_SECRET_HASH" name="FLW_SECRET_HASH"
                                        class="form-control" value="{{ env('FLW_SECRET_HASH') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Flutterwave') }}</label>
                                    <select id="enable_flutterwave" class="form-control select2"
                                        name="enable_flutterwave" data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('enable_flutterwave') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('enable_flutterwave') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- flutterwave settings -->

                        <!-- duitku settings-->
                        <div class="card mb-4" id="section-9">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Duitku Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="duitku">

                                <div class="mb-3">
                                    <label for="DUITKU_API_KEY"
                                        class="form-label">{{ localize('Duitku Api Key') }}</label>
                                    <input type="hidden" name="types[]" value="DUITKU_API_KEY">
                                    <input type="text" id="DUITKU_API_KEY" name="DUITKU_API_KEY" class="form-control"
                                        value="{{ env('DUITKU_API_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="DUITKU_MERCHANT_CODE"
                                        class="form-label">{{ localize('Duitku Merchant Code') }}</label>
                                    <input type="hidden" name="types[]" value="DUITKU_MERCHANT_CODE">
                                    <input type="text" id="DUITKU_MERCHANT_CODE" name="DUITKU_MERCHANT_CODE"
                                        class="form-control" value="{{ env('DUITKU_MERCHANT_CODE') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="DUITKU_CALLBACK_URL"
                                        class="form-label">{{ localize('Duitku Callback Url') }}</label>
                                    <input type="hidden" name="types[]" value="DUITKU_CALLBACK_URL">
                                    <input type="url" id="DUITKU_CALLBACK_URL" name="DUITKU_CALLBACK_URL"
                                        class="form-control" value="{{ url('/duitku/payment/callback') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="DUITKU_RETURN_URL"
                                        class="form-label">{{ localize('Duitku Return Url') }}</label>
                                    <input type="hidden" name="types[]" value="DUITKU_RETURN_URL">
                                    <input type="url" id="DUITKU_RETURN_URL" name="DUITKU_RETURN_URL"
                                        class="form-control" value="{{ url('/duitku/payment/return') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="DUITKU_ENV" class="form-label">{{ localize('Duitku Env') }}</label>
                                    <input type="hidden" name="types[]" value="DUITKU_ENV">
                                    <input type="url" id="DUITKU_ENV" name="DUITKU_ENV" class="form-control"
                                        value="{{ env('DUITKU_ENV') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Duitku') }}</label>
                                    <select id="enable_duitku" class="form-control select2" name="enable_duitku"
                                        data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('enable_duitku') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('enable_duitku') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- duitku settings -->


                        <!--yookassa settings-->
                        <div class="card mb-4" id="section-10">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Yookassa Credentials') }}</h5>
                                <input type="hidden" name="payment_methods[]" value="yookassa">
                                <div class="mb-3">
                                    <label for="YOOKASSA_SHOP_ID"
                                        class="form-label">{{ localize('Yookassa Shop ID') }}</label>
                                    <input type="hidden" name="types[]" value="YOOKASSA_SHOP_ID">
                                    <input type="text" id="YOOKASSA_SHOP_ID" name="YOOKASSA_SHOP_ID"
                                        class="form-control" value="{{ env('YOOKASSA_SHOP_ID') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="YOOKASSA_SECRET_KEY"
                                        class="form-label">{{ localize('Yookassa Secret Key') }}</label>
                                    <input type="hidden" name="types[]" value="YOOKASSA_SECRET_KEY">
                                    <input type="text" id="YOOKASSA_SECRET_KEY" name="YOOKASSA_SECRET_KEY"
                                        class="form-control" value="{{ env('YOOKASSA_SECRET_KEY') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="YOOKASSA_CURRENCY_CODE"
                                        class="form-label">{{ localize('YOOKASSA Currency Code') }}</label>
                                    <input type="hidden" name="types[]" value="YOOKASSA_CURRENCY_CODE">
                                    <input type="text" id="YOOKASSA_CURRENCY_CODE" name="YOOKASSA_CURRENCY_CODE"
                                        class="form-control" value="{{ env('YOOKASSA_CURRENCY_CODE') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Enable Yookassa') }}</label>
                                    <select id="enable_yookassa" class="form-control select2" name="enable_yookassa"
                                        data-toggle="select2">
                                        <option value="0"
                                            {{ getSetting('enable_yookassa') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                        <option value="1"
                                            {{ getSetting('enable_yookassa') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--yookassa settings-->


                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i> {{ localize('Save Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Payment Methods Settings') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">

                                    <li>
                                        <a href="#section-2" class="active">{{ localize('Paypal Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-3">{{ localize('Stripe Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-4">{{ localize('PayTm Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-5">{{ localize('Razorpay Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-6">{{ localize('IyZico Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-7">{{ localize('Paystack Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-8">{{ localize('FlutterWave Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-9">{{ localize('Duitku Credentials') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-10">{{ localize('Yookassa Credentials') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
