@php
    $yearlyCounter = \App\Models\Subscriptionpackage::isActive()
        ->where('package_type', 'yearly')
        ->count();
    $lifetimeCounter = \App\Models\Subscriptionpackage::isActive()
        ->where('package_type', 'lifetime')
        ->count();
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="tt-section-heading text-center mb-5">

                <h2 class="fw-bold fs-1 text-capitalize">{{ localize('Our Subscription Packages') }} <br>
                    <span class="tt-text-gradient-primary text-capitalize">{{ localize('Ready to get started?') }}</span>
                </h2>


                <ul class="list-unstyled list-inline tt-package-switch-list mt-4 z-2 position-relative" id="myTab"
                    role="tablist">
                    <li class="list-inline-item" role="presentation">
                        <input class="active" type="radio" name="tt-package-radio" id="tt-monthly"
                            data-bs-toggle="tab" data-bs-target="#tt-monthly-tab" role="tab"
                            aria-controls="tt-monthly" aria-selected="true" checked />
                        <label for="tt-monthly">{{ localize('Monthly') }}</label>
                    </li>

                    @if ($yearlyCounter > 0)
                        <li class="list-inline-item" role="presentation">
                            <input type="radio" name="tt-package-radio" id="tt-yearly" data-bs-toggle="tab"
                                data-bs-target="#tt-yearly-tab" role="tab" aria-controls="tt-yearly"
                                aria-selected="true" />
                            <label for="tt-yearly">{{ localize('Yearly') }}</label>
                        </li>
                    @endif
                    @if ($lifetimeCounter > 0)
                        <li class="list-inline-item" role="presentation">
                            <input type="radio" name="tt-package-radio" id="tt-lifetime" data-bs-toggle="tab"
                                data-bs-target="#tt-lifetime-tab" role="tab" aria-controls="home"
                                aria-selected="true" />
                            <label for="tt-lifetime">{{ localize('Lifetime') }}</label>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </div>

    <div class="row justify-content-center tab-content" id="myTabContent">
        <div class="col-lg-10 tab-pane fade show active" id="tt-monthly-tab" role="tabpanel"
            aria-labelledby="tt-monthly-tab">
            <div class="row g-3">
                @foreach ($packages as $package)
                    @if ($package->package_type == 'starter' || $package->package_type == 'monthly')
                        <div class="col-lg-4">
                            @include('frontend.default.pages.partials.home.pricing-card', [
                                'package' => $package,
                            ])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- yearly tab --}}
        <div class="col-lg-10 tab-pane fade" id="tt-yearly-tab" role="tabpanel" aria-labelledby="tt-yearly-tab">
            <div class="row g-3">
                @foreach ($packages as $package)
                    @if ($package->package_type == 'yearly')
                        <div class="col-lg-4">
                            @include('frontend.default.pages.partials.home.pricing-card', [
                                'package' => $package,
                            ])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- lifetime tab --}}
        <div class="col-lg-10 tab-pane fade" id="tt-lifetime-tab" role="tabpanel" aria-labelledby="tt-lifetime-tab">
            <div class="row g-3">
                @foreach ($packages as $package)
                    @if ($package->package_type == 'lifetime')
                        <div class="col-lg-4">
                            @include('frontend.default.pages.partials.home.pricing-card', [
                                'package' => $package,
                            ])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@section('modals-common')
    <!-- Modal -->
    <div class="modal fade" id="packagePaymentModal" tabindex="-1" aria-labelledby="packagePaymentModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packagePaymentModalLabel">{{ localize('Select Payment Method') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-5">
                    <form action="{{ route('website.subscriptions.subscribe') }}" method="POST"
                        class="payment-method-form">
                        @csrf
                        <input type="hidden" name="package_id" value="" class="payment_package_id">
                        <div class="form-input d-flex justify-content-center">
                            <select class="form-select w-50" id="payment_method" name="payment_method" required>
                                <option value="">{{ localize('Select payment method') }}
                                </option>

                                <!--Paypal-->
                                @if (getSetting('enable_paypal') == 1)
                                    <option value="paypal">
                                        {{ localize('Paypal') }}
                                    </option>
                                @endif

                                <!--stripe-->
                                @if (getSetting('enable_stripe') == 1)
                                    <option value="stripe">
                                        {{ localize('Stripe') }}
                                    </option>
                                @endif

                                <!--paytm-->
                                @if (getSetting('enable_paytm') == 1)
                                    <option value="paytm">
                                        {{ localize('Paytm') }}
                                    </option>
                                @endif

                                <!--razorpay-->
                                @if (getSetting('enable_razorpay') == 1)
                                    <option value="razorpay">
                                        {{ localize('Razorpay') }}
                                    </option>
                                @endif

                                <!--iyzico-->
                                @if (getSetting('enable_iyzico') == 1)
                                    <option value="iyzico">
                                        {{ localize('IyZico') }}
                                    </option>
                                @endif

                                <!--paystack-->
                                @if (getSetting('enable_paystack') == 1)
                                    <option value="paystack">
                                        {{ localize('paystack') }}
                                    </option>
                                @endif

                                <!--flutterwave-->
                                @if (getSetting('enable_flutterwave') == 1)
                                    <option value="flutterwave" class="text-capitalize">
                                        {{ localize('Flutterwave') }}
                                    </option>
                                @endif

                                <!--duitku-->
                                @if (getSetting('enable_duitku') == 1)
                                    <option value="duitku" class="text-capitalize">
                                        {{ localize('Duitku') }}
                                    </option>
                                @endif

                                <!--Yookassa-->
                                @if (getSetting('enable_yookassa') == 1)
                                    <option value="yookassa">
                                        {{ localize('Yookassa') }}
                                    </option>
                                @endif
                            </select>

                            <button type="submit" class="btn btn-primary ms-2">{{ localize('Proceed') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-common')
    <script>
        "use strict";

        // handle package payment
        function handlePackagePayment($this) {
            let package_id = $($this).data('package-id');
            let price = parseFloat($($this).data('price'));
            $('.payment_package_id').val(package_id);

            let isLoggedIn = parseInt('{{ Auth::check() }}');
            let authUserType = 'customer';

            if (isLoggedIn == 1) {
                authUserType = "{{ Auth::user()->user_type ?? 'customer' }}";
                if (authUserType == "customer") {
                    if (price > 0) {
                        showPackagePaymentModal();
                    } else {
                        $('.payment-method-form').submit();
                    }
                } else {
                    var redirectLink = "{{ route('subscriptions.index') }}";
                    $(location).prop('href', redirectLink)
                }
            } else {
                var redirectLink = "{{ route('subscriptions.index') }}";
                $(location).prop('href', redirectLink)
            }
        }

        // show package payment modal
        function showPackagePaymentModal() {
            $('#packagePaymentModal').modal('show')
        }

        // on submit payment form
    </script>
@endsection
