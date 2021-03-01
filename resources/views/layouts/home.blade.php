<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="navbar-full">
        <nav class="navbar fixed-top navbar-expand-md navbar-light navbar-laravel py-3">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home.start')}}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto mt-0">

                    </ul>
                    <ul class="navbar-nav ml-auto mt-0">
                        @if(false)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home.features')}}">Funktionen</span></a>
                            </li>
                        @endif
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-primary" href="{{ route('register') }}">Jetzt testen</a>
                            </li>
                            <li class="nav-item">
                                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="{{ route('app')}}">
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <main class="mt-5 py-4">
        @yield('content')
    </main>
    <footer class="p-5 bg-dark text-white">
        <div class="container text-center">
            <div class="container text-center d-flex justify-content-between">
                <div>Rechnungspilot - made with <i class="fas fa-fw fa-heart"></i> by <a class="text-white" href="https://d15r.de" target="_blank">D15r</a></div>
                <a class="text-white" href="/impressum">Impressum & Datenschutz</a>
            </div>
        </div>
    </footer>
</body>
</html>