<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Theme color -->
    <meta name="theme-color" content="#000" />

    <!-- PWA Manifest -->
    <link rel="manifest" href="/pwa/manifest.json"></link>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">

    <!-- Only for iOS: Configs -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Rechnungspilot">

     <!-- Icons -->
    <link rel="apple-touch-icon" href="/pwa/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" href="/pwa/icons/apple-icon-120x120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="/pwa/icons/apple-icon-152x152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="/pwa/icons/apple-icon-180x180.png" sizes="180x180">
    <link rel="apple-touch-icon" href="/pwa/icons/apple-icon-1024x1024.png" sizes="1024x1024">

    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link type="text/css" href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <div id="app" style="">

        <nav id="nav" class="d-flex flex-column" style="">
            <div class="text-center py-3">
                <img src="/images/logos/logo.svg" width="50">
            </div>
            <ul class="col">
                <a href="{{ url('/') }}"><li>Start</li></a>
                <a href="" data-toggle="collapse" data-target="#nav-belege"><li class="d-flex align-items-center justify-content-between line-height-base">Belege<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-belege" class="collapse">
                    <!-- <a href="{{ route('receipt.inquiry.index') }}"><li>Anfragen</li></a> -->
                    <!-- <a href="{{ route('receipt.quote.index') }}"><li>Angebote</li></a> -->
                    <!-- <a href="{{ route('receipt.order.index') }}"><li>Aufträge</li></a> -->
                    <a href="{{ \App\Receipts\Expense::indexPath() }}"><li>{{ \App\Receipts\Expense::label() }}</li></a>
                    <!-- <a href="{{ url('/briefe') }}"><li>Briefe</li></a> -->
                    <!-- <a href="{{ url('/lieferscheine') }}"><li>Lieferscheine</li></a> -->
                    <a href="{{ \App\Receipts\Invoice::indexPath() }}"><li>{{ \App\Receipts\Invoice::label() }}</li></a>
                    <!-- <a href="{{ url('/einnahmen') }}"><li>Einnahmen</li></a> -->
                </ul>
                <a href="{{ url('/projekte') }}"><li>Projekte</li></a>
                <a href="" data-toggle="collapse" data-target="#nav-stammdaten"><li class="d-flex align-items-center justify-content-between line-height-base">Stammdaten<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-stammdaten" class="collapse">
                    <a href="{{ \App\Item::indexPath() }}"><li>{{ \App\Item::label() }}</li></a>
                    <a href="{{ url('/dateien') }}"><li>Dateien</li></a>
                    <a href="{{ \App\Contacts\Contact::indexPath() }}"><li>{{ \App\Contacts\Contact::label() }}</li></a>
                    <a href="{{ \App\Banks\Account::indexPath() }}"><li>{{ \App\Banks\Account::label() }}</li></a>
                    <a href="{{ \App\User::indexPath() }}"><li>{{ \App\User::label() }}</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-buchhaltung"><li class="d-flex align-items-center justify-content-between line-height-base">Buchhaltung<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-buchhaltung" class="collapse">
                    <a href="{{ url('/buchungen') }}"><li>Buchungen</li></a>
                    <a href="{{ url('/forderungen') }}"><li>Forderungen</li></a>
                    <a href="{{ url('/verbindlichkeiten') }}"><li>Verbindlichkeiten</li></a>
                    <a href="{{ url('/mahnungen') }}"><li>Mahnungen</li></a>
                </ul>
                <li class="divider"></li>
                <a href="" data-toggle="collapse" data-target="#nav-einstellungen"><li class="d-flex align-items-center justify-content-between line-height-base">Einstellungen<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-einstellungen" class="collapse">
                    <a href="{{ url('/tokens') }}"><li>API Token</li></a>
                    <a href="{{ url('/einstellungen/buchhaltung') }}"><li>Buchhaltung</li></a>
                    <!-- <a href="{{ url('/einstellungen/finanzielles') }}"><li>Finanzielles</li></a> -->
                    <a href="{{ url('/firma/edit') }}"><li>Firma</li></a>
                    <a href="{{ url('/firmen') }}"><li>Firmen</li></a>
                    <a href="{{ url('/einstellungen/mahnstufen') }}"><li>Mahnstufen</li></a>
                    <a href="{{ url('/einstellungen/nummernkreise') }}"><li>Nummernkreise</li></a>
                    <a href="{{ url('/textbausteine') }}"><li>Textbausteine</li></a>
                    <a href="{{ url('/vorlagen/edit') }}"><li>Vorlagen</li></a>
                </ul>
            </ul>

            <div class="px-3 text-white text-center"><p>by <a href="https://d15r.de" target="_blank">D15r</a></p></div>
            <div class="bg-secondary text-white p-2 d-flex justify-content-around">
                <a class="text-white" href="{{ route('impressum') }}">Impressum & Datenschutz</a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>

        <div id="content-container">

            <nav class="navbar navbar-expand navbar-light bg-light sticky-top shadow-sm">
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <span class="navbar-text" id="menu-toggle">
                        <i class="fas fa-bars pointer"></i>
                    </span>
                    <form class="form-inline col my-2 my-lg-0">
                        <!-- <input class="form-control mr-sm-2 col d-none d-sm-block" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-primary my-2 my-sm-0 d-none d-sm-block" type="submit">Search</button> -->
                    </form>
                    <ul class="navbar-nav mr-auto mt-0 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-xs-none d-sm-none d-md-inline d-lg-inline d-xl-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('tokens.index') }}">API Tokens</a>
                                <a class="dropdown-item" href="{{ url('/aufgaben') }}">Aufgaben</a>
                                <a class="dropdown-item" href="{{ url('/kalender') }}">Kalender</a>
                                <a class="dropdown-item" href="{{ url('/zeiten') }}">Zeiten</a>
                                <a class="dropdown-item" href="{{ route('time.recording.index') }}" target="_blank">Zeiterfassung starten</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Abmelden
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div id="content" class="container-fluid mt-3" style="height: 100vh;">

                <div class="row align-items-center mb-3">
                    <h2 class="col my-0">@yield('title')</h2>
                    <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">@yield('buttons')</div>
                </div>

                @yield('content')
            </div>

        </div>
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