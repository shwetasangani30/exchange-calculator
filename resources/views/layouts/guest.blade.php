<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="authenticity_token" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title itemprop="name">
        Exchange Calculator
    </title>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">

    <link id="pagestyle" href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
    @yield('content')
    <!-- JQuery -->
    <script src="{{ asset('js/jquery/jquery-3.6.4.min.js') }}"></script>
    <!-- Jquery Validate -->
    <script src="{{ asset('js/jquery/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/core/popper.min.js')}}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script async defer src="{{ asset('js/plugins/buttons.js')}}"></script>

    <script src="{{ asset('js/custom.js')}}"></script>

    @stack('footer_scripts')
</body>

</html>