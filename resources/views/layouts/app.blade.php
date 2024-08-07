<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Osiset\ShopifyApp\Util::getShopifyConfig('app_name') }}</title>
    @yield('styles')

    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('storage/public/images/logo.png') }}" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('dashboard-assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('dashboard-assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{ asset('dashboard-assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/css/toastr.css') }}" rel="stylesheet">

    @stack('css')

</head>

<body>



    <!-- ======= Header ======= -->
    {{-- @include('layouts.partial.header') --}}


    <!-- ======= Sidebar ======= -->
    {{-- @include('layouts.partial.sidebar') --}}


    <main id="-main" class="main px-4 py-4" id="app">

        @yield('heading')
        @yield('content')
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    @if (\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled') && \Osiset\ShopifyApp\Util::useNativeAppBridge())
        <script
            src="{{ config('shopify-app.appbridge_cdn_url') ?? 'https://unpkg.com' }}/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@' . config('shopify-app.appbridge_version') : '' }}">
        </script>
        <script @if (\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled')) data-turbolinks-eval="false" @endif>
            var AppBridge = window['app-bridge'];
            var actions = AppBridge.actions;
            var utils = AppBridge.utilities;
            // var Redirect = actions.Redirect;
            var createApp = AppBridge.default;

            var app = createApp({
                apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name) }}",
                host: "{{ \Request::get('host') }}",
                forceRedirect: true,
            });

            var host = "{{ \Request::get('host') }}";
            // alert(host)

            // var redirect = Redirect.create(app);
        </script>

        @include('shopify-app::partials.token_handler')
        @include('shopify-app::partials.flash_messages')
    @endif



    <!-- Vendor JS Files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('dashboard-assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('dashboard-assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('dashboard-assets/js/main.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/moment.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/toastr.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>


    @stack('scripts')

</body>

</html>
