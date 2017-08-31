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

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    @yield('styles')

    <!-- Google Optimize -->
    <style>.async-hide { opacity: 0 !important} </style>
    <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
    h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
    (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
    })(window,document.documentElement,'async-hide','dataLayer',4000,
    {'GTM-P4Q7LGP':true});</script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-47769770-1', 'auto');
        ga('require', 'GTM-P4Q7LGP');
        ga('send', 'pageview');
    </script>
</head>
<body>
    <div id="app">
        @yield('layout')

        <div class="shepherd-backdrop"></div>

        <alert v-model="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="350px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>@{{ message }}</p>
        </alert>

        <alert v-model="showInfo"
               placement="top-right"
               :duration="3000"
               type="info"
               width="350px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Hey!</strong>
            <p>@{{ message }}</p>
        </alert>

        <alert v-model="showError"
               placement="top-right"
               :duration="0"
               type="danger"
               width="350px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>@{{ message }}</p>
        </alert>
    </div>

    @yield('tour')
    <script src="{{ elixir('js/main.js') }}"></script>
    <script src="{{ asset('/js/vendor.js') }}"></script>
    @yield('scripts')

    <!-- Hotjar Tracking Code for https://missions.me -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:587057,hjsv:5};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
</body>
</html>
