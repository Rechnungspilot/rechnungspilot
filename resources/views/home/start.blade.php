@extends('layouts.home')

@section('content')
<div class="jumbotron">
    <div class="container">
        <h1 class="display-4">Rechnungspilot.de</h1>
        <p class="lead">Für Freelancer, Gründer und Kleinunternehmer</p>
        <p>Professionelle Rechnungen schreiben, Kontakte verwalten und Überblick über deine Finanzen behalten – alles mit deinem Rechnungsprogramm.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Kostenlos mitmachen</a>
    </div>
</div>
<div class="container">
    <div class="card-deck mb-3 text-center" style="min-width: 220px;">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Kostenlos & Open Source.</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Nutze Rechnungspilot kostenlos und hilf mit den Service zu verbessern.<br /><a href="https://github.com/Rechnungspilot">View on Github</a></p>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Ungewohnt vielfältig.</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Machen wir's doch erstmal kurz: Artikel, Kontakte, Ansprechpartner, Dateien, Team, Angebote, Aufträge, Lieferscheine, Rechnungen, Abos, Ausgaben, Einnahmen, Mahnungen, Briefe, Banking & Aufgaben</p>
                <div>
                    <a class="btn btn-secondary" href="/funktionen">Alle Funktionen</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection