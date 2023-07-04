@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Home') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <!--hero section start-->
    @include('frontend.default.pages.partials.home.hero')
    <!--hero section end-->

    <!--trusted client list start-->
    <div class="container">
        <section class="tt-clients">
            <div class="tt-client-wrap bg-white rounded-custom-top rounded-bottom p-5 ptb-60 shadow">
                @include('frontend.default.pages.partials.home.trustedBy')
            </div>
        </section>
    </div>
    <!--trusted client list end-->

    <!--how it work section start-->
    @include('frontend.default.pages.partials.home.howItWorks')
    <!--how it work section end-->

    <!--best features section start-->
    <section class="tt-best-features pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="tt-section-heading text-center mb-5">
                        <h2 class="fw-bold fs-1">{{ localize('Our Best Features') }}
                            <div class="tt-text-gradient-primary">{{ localize('We are more powerful than others') }}
                            </div>
                        </h2>
                    </div>
                </div>
            </div>

            @include('frontend.default.pages.partials.home.features')
        </div>
    </section>
    <!--best features section end-->

    <!--templates section start-->
    @include('frontend.default.pages.partials.home.templates')
    <!--templates section end-->

    <!--testimonial seciton start-->
    <section class="tt-testimonial-section ptb-100 bg-secondary-subtle">
        @include('frontend.default.pages.partials.home.testimonials')
    </section>
    <!--testimonial seciton end-->

    <!--our pricing plan start-->
    <section class="tt-pricing-section pt-100">
        @include('frontend.default.pages.partials.home.pricing')
    </section>
    <!--our pricing plan end-->

    <!--cta start-->
    <section class="cta-subscribe ptb-100">
        @include('frontend.default.pages.partials.home.cta')
    </section>
    <!--cta end-->
@endsection
