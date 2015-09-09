<!DOCTYPE html>
<html>
    <head>
        <title>Donate App - @yield('title')</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">  
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    </head>
    <body class="donate admin">
        @include('admin.templates.menu')
        <main>
            @yield('content')
        </main>
    </body>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
