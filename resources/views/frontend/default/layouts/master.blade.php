<!DOCTYPE html>

@php
    $locale = str_replace('_', '-', app()->getLocale()) ?? 'en';
    $localLang = \App\Models\Language::where('code', $locale)->first();
@endphp
@if ($localLang->is_rtl == 1)
    <html dir="rtl" lang="{{ $locale }}" data-bs-theme="light">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
@endif

<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--favicon icon-->
    <link rel="shortcut icon" href="{{ uploadedAsset(getSetting('favicon')) }}">

    <!--meta-->
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ getSetting('global_meta_description') }}">
    <meta name="keywords" content="{{ getSetting('global_meta_keywords') }}">


    <!--title-->
    <title>
        @yield('title', getSetting('system_title'))
    </title>

    @yield('meta')

    @if (!isset($blog))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ getSetting('global_meta_title') }}" />
        <meta itemprop="description" content="{{ getSetting('global_meta_description') }}" />
        <meta itemprop="image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}" />

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product" />
        <meta name="twitter:site" content="@publisher_handle" />
        <meta name="twitter:title" content="{{ getSetting('global_meta_title') }}" />
        <meta name="twitter:description" content="{{ getSetting('global_meta_description') }}" />
        <meta name="twitter:creator"
            content="@author_handle"/>
    <meta name="twitter:image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}"/>

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ getSetting('global_meta_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}" />
    <meta property="og:description" content="{{ getSetting('global_meta_description') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" /> 
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endif

    <!-- head-scripts -->
    @include('frontend.default.inc.head-scripts')
    <!-- head-scripts -->

    <!--build:css-->
    @include('frontend.default.inc.css', ['localLang' => $localLang])
    <!-- endbuild -->  
</head>

<body>  

    @if (getSetting('enable_cookie_consent') == '1')
        <div class="cookie-alert">
            <div class="p-3 bg-white rounded shadow-lg">
                <div class="text-white mb-3">
                    {!! getSetting('cookie_consent_text') !!}
                </div>
                <button class="btn btn-primary cookie-accept">
                    {{ localize('I Understood') }}
                </button>
            </div>
        </div>
    @endif

    <!--preloader start-->
    @if (getSetting('enable_preloader') != '0')
      <div id="preloader" class="bg-light-subtle">
        <div class="preloader-wrap">
            <img src="{{ uploadedAsset(getSetting('navbar_logo_dark')) }}" class="img-fluid">
            <div class="loading-bar"></div>
        </div>
    </div>
    @endif
    <!--preloader end--> 
   

    <!--main content wrapper start-->
    <main class="tt-main-wrapper position-relative z-1">
        <!--header section start-->
        @if (!isset($exception)) @include('frontend.default.inc.header') @endif
        <!--header section end--> 
 
        <!--contents start -->
        @yield('contents')
        <!--contents end--> 
        
        <!--scroll to top -->
        <div class="tt-scroll-top scroll-to-target" data-target="html">
            <img src="{{ staticAsset('frontend/default/assets/img/website/back-to-top.png') }}" alt="back to top" class="img-fluid">
        </div>

        <!--footer section start-->
         @if (!isset($exception)) @include('frontend.default.inc.footer') @endif
        <!--footer section end-->
        </main>

        <!--modals-->
        @yield('modals')
        <!--modals--> 
        
    <!-- Offcanvas -->
    <div class="offcanvas
            offcanvas-end subscription-templates-form mb-0" id="offcanvasRight" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <div>
                    <h5 class="offcanvas-title">{{ localize('Package Templates') }}</h5>
                </div>
                <div>
                    <span class="btn btn-soft-danger offcanvasRightClose" data-bs-dismiss="offcanvas">
                        {{ localize('Close') }}
                    </span>

                </div>
            </div>
        </div>
        <div class="offcanvas-body" data-simplebar>
            <div class="text-center template-please-wait mt-5">{{ localize('Please wait') }}...</div>
            <div class="package-template-contents"></div>
        </div>
        </div> <!-- offcanvas template end-->

        <!--build:js-->
        @include('frontend.default.inc.scripts')
        <!--endbuild-->

        <!--page's scripts-->
        @yield('scripts')
        <!--page's script-->

        <!-- scripts for common layout - website-admin -->
        @yield('scripts-common')

        <!-- modals for common layout - website-admin -->
        @yield('modals-common')
        </body>

        </html>
