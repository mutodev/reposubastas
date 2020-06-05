<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
    </style>

    @yield('stylesheets')

    @if (!Auth::guest())
    <!-- Smartsupp Live Chat script -->
        <script type="text/javascript">
          var _smartsupp = _smartsupp || {};
          _smartsupp.key = '8e9639b7fc5dc2601f7712a1b673f4f4841faac1';
          window.smartsupp||(function(d) {
            var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
            s=d.getElementsByTagName('script')[0];c=d.createElement('script');
            c.type='text/javascript';c.charset='utf-8';c.async=true;
            c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
          })(document);
        </script>
    @endif
</head>
<body>
<div id="app" style="overflow: hidden;">
    @yield('main')
</div>

@yield('footer_scripts')

@yield('footer')
</body>
</html>
