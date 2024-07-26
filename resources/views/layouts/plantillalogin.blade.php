<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/faviconMoms.png')}}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- VITE CSS -->
    @vite(['resources/sass/login.scss'])
    <style>
        body{
            background-image: url({{asset('img/fondoLogin.png')}});
        }
    </style>
</head>
<body class="hold-transition @yield('bodystyle')">
    <div class="@yield('boxstyle')">
        @yield('content')
    </div>
    <!-- /.login-box -->    
    <!-- VITE Scripts -->
    @vite(['resources/js/login.js'])
</body>
</html>
