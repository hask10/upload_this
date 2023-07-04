@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Contact Us') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('page-header-title')
    {{ localize('Contact Us') }}
@endsection


@section('contents')
    <!--page header-->
    @include('frontend.default.inc.page-header')

    <!--trusted client list start-->
    <section class="tt-clients bg-secondary-subtle">
        <div class="container">
            <div class="tt-client-wrap bg-white rounded-custom-top rounded-bottom p-5 ptb-60 shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div
                            class="tt-contact-promo p-5 bg-white rounded-custom custom-shadow text-center d-flex flex-column h-100">
                            <div class="text-center mb-4">
                                <i data-feather="message-square" class="text-primary"></i>
                            </div>
                            <div class="contact-promo-info mb-4">
                                <h5>{{ localize('Chat with Us') }}</h5>
                                <p>
                                    {{ localize('Get connected to us, we are happy to hear from you.') }}
                                </p>
                            </div>
                            <a href="mailto:{{ getSetting('contact_email') }}" class="btn btn-secondary mt-auto">
                                {{ localize('Chat with us') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div
                            class="tt-contact-promo p-5 bg-white rounded-custom custom-shadow text-center d-flex flex-column h-100">
                            <div class="text-center mb-4">
                                <i data-feather="mail" class="text-primary"></i>
                            </div>
                            <div class="contact-promo-info mb-4">
                                <h5>{{ localize('Email Us') }}</h5>
                                <p>{{ localize('Drop us an email and you\'ll receive a reply within a short time.') }}</p>
                            </div>
                            <a href="mailto:{{ getSetting('contact_email') }}"
                                class="btn btn-primary mt-auto">{{ localize('Email Us') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div
                            class="tt-contact-promo p-5 bg-white rounded-custom custom-shadow text-center d-flex flex-column h-100">
                            <div class="text-center mb-4">
                                <i data-feather="phone" class="text-primary"></i>
                            </div>
                            <div class="contact-promo-info mb-4">
                                <h5>{{ localize('Give us a call') }}</h5>
                                <p>{{ localize('Give us a call. Our Experts are ready to talk to you.') }}
                                </p>
                            </div>
                            <a href="tel:{{ getSetting('contact_phone') }}"
                                class="btn btn-secondary mt-auto">{{ getSetting('contact_phone') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--trusted client list end-->

    <!--contact us section start-->
    <section class="tt-contact-us ptb-100 bg-secondary-subtle">
        <div class="container">
            <div class="row justify-content-lg-between align-items-center">
                <div class="col-lg-6 col-md-8">
                    <div class="section-heading">
                        <h2>{{ localize('Talk to Our Team') }}</h2>
                        <p>{{ localize('Write to us, we are happy to assist you about your queries.') }}</p>
                    </div>
                    <form action="{{ route('contactUs.store') }}" class="register-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="mb-1"> {{ localize('Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input class="form-control" type="text" class="form-control" name="name"
                                        placeholder="{{ localize('Your name') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="email" class="mb-1">{{ localize('Email') }}<span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input class="form-control" type="email" name="email"
                                        placeholder="{{ localize('You email') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="phone" class="mb-1">{{ localize('Phone') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input class="form-control" type="text" name="phone"
                                        placeholder="{{ localize('You phone') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="yourMessage" class="mb-1">{{ localize('Messages') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <textarea class="form-control" name="message" placeholder="{{ localize('Write your message') }}" rows="6"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ localize('Get in Touch') }}</button>
                    </form>
                </div>
                <div class="col-lg-5 col-md-10">
                    <div class="contact-us-img">
                        <img src="{{ staticAsset('frontend/default/assets/img/website/contact-us.svg') }}" alt="contact us"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--contact us section end-->

    <!--cta start-->
    <section class="cta-subscribe pb-100 bg-secondary-subtle">
        @include('frontend.default.pages.partials.home.cta')
    </section>
    <!--cta end-->
@endsection
