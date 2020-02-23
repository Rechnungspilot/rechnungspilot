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
            <ul class="col">
                <a href="{{ url('/') }}"><li>Start</li></a>
                <a href="" data-toggle="collapse" data-target="#nav-angebote"><li class="d-flex align-items-center justify-content-between line-height-base">Akquise<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-angebote" class="collapse">
                    <a href="{{ route('receipt.inquiry.index') }}"><li>Anfragen</li></a>
                    <a href="{{ route('receipt.quote.index') }}"><li>Angebote</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-auftraege"><li class="d-flex align-items-center justify-content-between line-height-base">Auftragsmanagement<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-auftraege" class="collapse">
                    <a href="{{ route('receipt.order.index') }}"><li>Aufträge</li></a>
                    <a href="{{ url('/lieferscheine') }}"><li>Lieferscheine</li></a>
                    <a href="{{ url('/zeiten') }}"><li>Zeiten</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-personal"><li class="d-flex align-items-center justify-content-between line-height-base">Personalplanung<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-personal" class="collapse">
                    <a href="{{ url('/kalender') }}"><li>Kalender</li></a>
                    <a href="{{ url('/projekte') }}"><li>Projekte</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-rechnungswesen"><li class="d-flex align-items-center justify-content-between line-height-base">Rechnungswesen<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-rechnungswesen" class="collapse">
                    <a href="{{ url('/abos') }}"><li>Abos</li></a>
                    <a href="{{ url('/rechnungen') }}"><li>Rechnungen</li></a>
                    <a href="{{ url('/einnahmen') }}"><li>Einnahmen</li></a>
                    <a href="{{ url('/forderungen') }}"><li>Forderungen</li></a>
                    <a href="{{ url('/buchungen') }}"><li>Buchungen</li></a>
                    <a href="{{ url('/mahnungen') }}"><li>Mahnungen</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-after-sales"><li class="d-flex align-items-center justify-content-between line-height-base">After Sales<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-after-sales" class="collapse">
                    <a href="{{ url('/briefe') }}"><li>Briefe</li></a>
                    <a href="{{ url('/projekte') }}"><li>Projekte</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-kosten"><li class="d-flex align-items-center justify-content-between line-height-base">Kosten<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-kosten" class="collapse">
                    <a href="{{ url('/ausgaben') }}"><li>Ausgaben</li></a>
                    <a href="{{ url('/verbindlichkeiten') }}"><li>Verbindlichkeiten</li></a>
                    <a href="{{ url('/buchungen') }}"><li>Buchungen</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-stammdaten"><li class="d-flex align-items-center justify-content-between line-height-base">Stammdaten<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-stammdaten" class="collapse">
                    <a href="{{ url('/artikel') }}"><li>Artikel</li></a>
                    <a href="{{ url('/dateien') }}"><li>Dateien</li></a>
                    <a href="{{ url('/kontakte') }}"><li>Kontakte</li></a>
                    <a href="{{ url('/konten') }}"><li>Konten</li></a>
                    <a href="{{ url('/team') }}"><li>Team</li></a>
                </ul>
                <a href="" data-toggle="collapse" data-target="#nav-import"><li class="d-flex align-items-center justify-content-between line-height-base">Import<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-import" class="collapse">
                    <a href="{{ url('/import/artikel') }}"><li>Artikel</li></a>
                    <a href="{{ url('/import/kontakte') }}"><li>Kontakte</li></a>
                    <a href="{{ url('/import/philip') }}"><li>Philip</li></a>
                </ul>
                <li class="divider"></li>
                <a href="" data-toggle="collapse" data-target="#nav-einstellungen"><li class="d-flex align-items-center justify-content-between line-height-base">Einstellungen<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-einstellungen" class="collapse">
                    <a href="{{ url('/einstellungen/buchhaltung') }}"><li>Buchhaltung</li></a>
                    <!-- <a href="{{ url('/einstellungen/finanzielles') }}"><li>Finanzielles</li></a> -->
                    <a href="{{ url('/firma/edit') }}"><li>Firma</li></a>
                    <a href="{{ url('/einstellungen/mahnstufen') }}"><li>Mahnstufen</li></a>
                    <a href="{{ url('/einstellungen/nummernkreise') }}"><li>Nummernkreise</li></a>
                    <a href="{{ url('/textbausteine') }}"><li>Textbausteine</li></a>
                    <a href="{{ url('/vorlagen/edit') }}"><li>Vorlagen</li></a>
                </ul>
                @admin
                    <li class="divider"></li>
                    <a href="{{ url('/berichte') }}"><li>Berichte</li></a>
                    <a href="{{ url('/handbuch') }}"><li>Handbuch</li></a>
                    <a href="{{ url('/roadmap') }}"><li>Roadmap</li></a>

                    <a href="" data-toggle="collapse" data-target="#nav-admin"><li class="d-flex align-items-center justify-content-between line-height-base">Admin<i class="fas fa-caret-right"></i></li></a>
                    <ul id="nav-admin" class="collapse">
                        <a href="{{ url('/firmen') }}"><li>Firmen</li></a>
                        <a href="{{ url('/guthaben') }}"><li>Guthaben</li></a>
                    </ul>
                @endadmin
            </ul>

            <div class="bg-secondary text-white p-2 d-flex justify-content-around">
                <a class="text-white" href="{{ route('impressum') }}">Impressum & Datenschutz</a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>

        <div id="content-container">

            <header>
                <h1>@yield('title')</h1>
                <div id="settings">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-body" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('/aufgaben') }}">Aufgaben</a>
                            <a class="dropdown-item" href="{{ route('time.recording.index') }}" target="_blank">Zeiterfassung starten</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Abmelden
                            </a>
                        </div>
                    </div>
                    <div id="menu-toggle" class="pointer"><i class="fas fa-bars"></i></div>
                </div>
            </header>

            <div id="content" class="container-fluid" style="height: 100vh;">
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