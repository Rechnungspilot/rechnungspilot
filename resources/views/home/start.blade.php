@extends('layouts.home')

@section('content')
<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <h1 class="display-6">Rechnungspilot.de</h1>
                <p class="lead">Für Freelancer, Gründer und Kleinunternehmer</p>
                <p>Professionelle Rechnungen schreiben, Kontakte verwalten und Überblick über deine Finanzen behalten – alles mit deinem Rechnungsprogramm.</p>
                <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Kostenlos mitmachen</a>
            </div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center"><img class="img-fluid" src="{{ Storage::url('screenshot-start.png') }}"></div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card-deck mb-3 text-center" style="min-width: 220px;">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Kostenlos & Open Source.</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Ich nutze Rechnungspilot, um mein Gewerbe zu verwalten. Vielleicht hilft es dir auch.</p>
                <p>Die Software ist Open Source und ich freue mich über PRs.</p>
                <div>
                    <a class="btn btn-secondary" href="https://github.com/Rechnungspilot/rechnungspilot" target="_blank">Github</a>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Funktionen.</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Artikel, Kontakte, Ansprechpartner, Dateien, Team, Rechnungen, Abos, Ausgaben, Mahnungen, Banking & Aufgaben</p>
                <p>Wenn Du Fragen hast oder eine individuelle Lösung benötigst schreib mir gerne eine Nachricht.</p>
                <div>
                    <a class="btn btn-secondary" href="contact">Kontakt</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection