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

     <script src="{{ elixir('js/main.js') }}"></script>
    {{--<script src="/js/main.js"></script>--}}
    <script src="/js/vendor.js"></script>
    @yield('scripts')
</body>
</html>
