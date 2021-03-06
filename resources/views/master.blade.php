<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    @hasSection('title')
      <title>@yield('title')</title>
    @else
      {!! SEO::generate() !!}
    @endif

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">

    @yield('styles')

    @prod
        <!-- Google Analytics -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-47769770-1', 'auto');
            ga('require', 'GTM-P4Q7LGP');
            ga('send', 'pageview');
        </script>

        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '500088313694914');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                    src="https://www.facebook.com/tr?id=500088313694914&ev=PageView&noscript=1"/></noscript>
        <!-- End Facebook Pixel Code -->
    @endprod

    @include('vars')
</head>
<body>
    <div id="app" data-auth="{{ auth()->check() ? 'true' : 'false' }}">
        @yield('layout')

        <div class="shepherd-backdrop"></div>

        <alert v-model="showSuccess" v-cloak
               placement="top-right"
               :duration="3000"
               type="success"
               width="350px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>@{{ message }}</p>
        </alert>

        <alert v-model="showInfo" v-cloak
               placement="top-right"
               :duration="3000"
               type="info"
               width="350px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Hey!</strong>
            <p>@{{ message }}</p>
        </alert>

        <alert v-model="showError" v-cloak
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
    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="{{ mix('/js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')

    <!-- <script>
        // Popup control
		$(document).ready(function(){
            if($.cookie('showpopup') == 'false'){
                $("#yearendpopup").hide();
            }

			$("#closepopup").click(function(){
                $("#yearendpopup").hide();
                var date = new Date();
                var minutes = 30;
                date.setTime(date.getTime() + (minutes * 60 * 100));
                $.cookie('showpopup', false, { expires: date });
			});
		});
	</script> -->
</body>
</html>
