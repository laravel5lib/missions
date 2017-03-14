<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>
      @hasSection('title')
        @yield('title')
      @else
        Missions.Me | Custom Group Missions Trips
      @endif
    </title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />

     <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    {{--<link href="/css/app.css" rel="stylesheet">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    {{--<script src="//d2wy8f7a9ursnm.cloudfront.net/bugsnag-3.min.js"
        data-apikey="0f5323b4f7858ae82155bb75688d8533"></script>--}}
    @yield('styles')
</head>
<body id="app">
    @yield('layout')
    <div class="shepherd-backdrop"></div>

    <alert :show.sync="showSuccess"
           placement="top-right"
           :duration="3000"
           type="success"
           width="350px"
           dismissable>
        <span class="icon-ok-circled alert-icon-float-left"></span>
        <strong>Good job!</strong>
        <p>@{{ message }}</p>
    </alert>

    <alert :show.sync="showError"
           placement="top-right"
           :duration="0"
           type="danger"
           width="350px"
           dismissable>
        <span class="icon-info-circled alert-icon-float-left"></span>
        <strong>Oh No!</strong>
        <p>@{{ message }}</p>
    </alert>

    @yield('tour')
    <script src="{{ elixir('js/main.js') }}"></script>
    {{--<script src="/js/main.js"></script>--}}
    <script src="/js/vendor.js"></script>
    @yield('scripts')
</body>
</html>
