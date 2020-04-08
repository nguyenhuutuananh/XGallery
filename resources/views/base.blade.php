<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>XGallery - {{$title}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('storage/vendor/AdminLTE/dist/css/adminlte.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css"/>
    <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"/>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="{{ asset('storage/vendor/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"/>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css"/>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
    @include('includes.head.metadata')
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
</head>
<body class="app-{{App::environment()}} sidebar-mini layout-fixed sidebar-open">
@include('includes.navbar.top')

@include('includes.navbar.sidebar')

<div class="content-wrapper" style="min-height: 400px; background: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 messages mt-2">
                @yield('content')
            </div>
        </div>
        <div aria-live="polite" aria-atomic="true" style="position: relative; z-index: 9999;">
            <!-- Position it -->
            <div style="position: fixed; top: 15px; right: 30px; z-index: 1" class="toast-container">

                <!-- Then put toasts within -->
            </div>
        </div>

    </div>
</div>

@include('includes.footer')
</body>
</html>
