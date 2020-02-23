<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">

    <title>{{ config('app.name', 'Laravel') }} Zeiterfassung</title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @yield('content')

        @if (session('status'))
            @if(is_array(session('status')))
                <flash-message :initial-message="{{ json_encode($status) }}"></flash-message>
            @else
                <flash-message :initial-message="{{ json_encode(['text' => session('status')]) }}"></flash-message>
            @endif
        @else
            <flash-message></flash-message>
        @endif
    </div>
</body>
</head>
</html>