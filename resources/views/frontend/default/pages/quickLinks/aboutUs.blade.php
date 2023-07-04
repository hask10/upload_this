@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('About Us') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('page-header-title')
    {{ localize('About Us') }}
@endsection


@section('contents')
    <!--page header-->
    @include('frontend.default.inc.page-header')

    <!--about us content start-->
    <section class="tt-about-us pb-100 position-relative bg-light-subtle">
        <div class="container">
            @include('frontend.default.pages.partials.home.features', ['mt' => true])
        </div>
    </section>
    <!--about us content end-->

    <!--trusted client list start-->
    <section class="tt-clients pb-100 bg-light-subtle">
        <div class="container">
            @include('frontend.default.pages.partials.home.trustedBy')
        </div>
    </section>
    <!--trusted client list end-->

    <!--testimonial seciton start-->
    <section class="tt-testimonial-section ptb-100 bg-secondary-subtle">
        @include('frontend.default.pages.partials.home.testimonials')
    </section>
    <!--testimonial seciton end-->

    <!--cta seciton start-->
    <section class="cta-action pb-100 bg-secondary-subtle position-relative">
        @include('frontend.default.pages.partials.home.cta')
    </section>
@endsection
