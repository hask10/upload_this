<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--favicon icon-->
    <link rel="shortcut icon" href="{{ uploadedAsset(getSetting('favicon')) }}">

    <!--title-->
    <title>
        @yield('title')
    </title>

    <!--build:css-->
    @include('backend.inc.styles')
    <!-- end build -->

</head>

<body>
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

    <!--sidebar section start-->
    @include('backend.inc.leftSidebar')
    <!--sidebar section end-->

    <!--main content wrapper start-->
    <main class="tt-main-wrapper bg-secondary-subtle"
        @if (!Route::is('admin.pos.index')) id="content" @else  id="pos-content" @endif>

        <!--header section start-->
        @include('backend.inc.navbar')
        <!--header section end-->

        <!-- Start Content-->
        @yield('contents')
        <!-- container -->

        <!--footer section start-->
        @include('backend.inc.footer')
        <!--footer section end-->

        <!-- media-manager -->
        @include('backend.inc.media-manager.media-manager')

    </main>
    <!--main content wrapper end-->

    <!-- modals -->
    @yield('modals')

    <!-- modals for common layout - website-admin -->
    @yield('modals-common')

    <!-- delete modal -->
    @include('backend.inc.deleteModal')

    <!--build:js-->
    @include('backend.inc.scripts')
    <!--endbuild-->

    <!-- scripts from different pages -->
    @yield('scripts')

    <!-- scripts for common layout - website-admin -->
    @yield('scripts-common')

    <!-- required scripts -->
    <script>
        "use strict";

        // change language
        function changeLocaleLanguage(e) {
            var locale = e.dataset.flag;
            $.post("{{ route('backend.changeLanguage') }}", {
                _token: '{{ csrf_token() }}',
                locale: locale
            }, function(data) {
                location.reload();
            });
        }


        // change currency
        function changeLocaleCurrency(e) {
            var currency_code = e.dataset.currency;
            $.post("{{ route('backend.changeCurrency') }}", {
                _token: '{{ csrf_token() }}',
                currency_code: currency_code
            }, function(data) {
                location.reload();
            });
        }

        // localize data
        function localizeData(langKey) {
            window.location = '{{ url()->current() }}?lang_key=' + langKey + '&localize';
        }

        // ajax toast 
        function notifyMe(level, message) {
            if (level == 'danger') {
                level = 'error';
            }
            toastr.options = {
                closeButton: true,
                newestOnTop: false,
                progressBar: true,
                positionClass: "toast-top-center",
                preventDuplicates: false,
                onclick: null,
                showDuration: "3000",
                hideDuration: "1000",
                timeOut: "2500",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
            };
            toastr[level](message);
        }

        //laravel flash toast messages 
        @foreach (session('flash_notification', collect())->toArray() as $message)
            notifyMe("{{ $message['level'] }}", "{{ $message['message'] }}");
        @endforeach
    </script>
</body>

</html>
