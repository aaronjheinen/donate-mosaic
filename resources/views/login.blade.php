<!DOCTYPE html>
<html>
    <head>
        <title>Donate App - @yield('title')</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body class="donate login">
        <nav class="top-nav">
            <div class="container">
                <div class="nav-wrapper">
                    @section('breadcrumbs')
                        <a href="#" class="page-title">Campaign Admin Panel</a>
                    @show
                </div>
            </div>
        </nav>
        <div class="container">
            @yield('content')
        </div>
    </body>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
