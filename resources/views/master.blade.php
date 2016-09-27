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

    <link href="/css/app.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')
</head>
<body id="app">
    
    @yield('layout')

    <script src="/js/main.js"></script>
    <script src="/js/vendor.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    @yield('scripts')
</body>
</html>
