<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>
      @hasSection('title')
        @yield('title')
      @else
        Missions.Me
      @endif
    </title>

     <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    {{--<link href="/css/app.css" rel="stylesheet">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('styles')
</head>
<body id="app">
    
    @yield('layout')

    <alert :show.sync="showSuccess"
           placement="top-right"
           :duration="3000"
           type="success"
           width="400px"
           dismissable>
        <span class="icon-ok-circled alert-icon-float-left"></span>
        <strong>Well Done!</strong>
        <p>@{{ message }}</p>
    </alert>

    <alert :show.sync="showError"
           placement="top-right"
           :duration="6000"
           type="danger"
           width="400px"
           dismissable>
        <span class="icon-info-circled alert-icon-float-left"></span>
        <strong>Oh No!</strong>
        <p>@{{ message }}</p>
    </alert>

     <script src="{{ elixir('js/main.js') }}"></script>
    {{--<script src="/js/main.js"></script>--}}
    <script src="/js/vendor.js"></script>
    @yield('scripts')
</body>
</html>
