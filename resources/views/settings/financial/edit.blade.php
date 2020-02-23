@extends('layouts.layout')

@section('title', 'Einstellungen > Finanzielles')

@section('content')

    @if($company->locked)
        <div class="alert alert-danger" role="alert">
            Der Account ist gesperrt. Bitte das Konto aufladen!
        </div>
    @endif

    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dein Wunschpreis</div>
                <div class="card-body">
                    <p>Das ist hier der Preis, den wir monatlich von deinem Rechungspilot-Konto abziehen. Zu hoch, zu niedrig? Zufrieden, unzufrieden? Spendabel, knapp bei Kasse? Es liegt bei dir, zu entscheiden, wieviel dir dein Rechungspilot wert ist. Du gehst keine Verpflichtung für die Zukunft ein und kannst den Preis jeden Monat ändern.</p>
                    <p>Dein aktueller <b>Mindestpreis liegt bei 1,00 €</b>
                    <form action="{{ url( 'einstellungen/finanzielles' ) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="price">Preis</label>
                            <input type="text" class="form-control {{ ($errors->has('price') ? 'is-invalid' : '') }}" id="price" name="price" value="{{ old('price') ?? number_format($company->price / 100, 2, ',', '.') }}">
                            @if ($errors->has('price'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('price') }}
                                </div>
                            @endif
                        </div>

                        <button class="btn btn-primary" type="submit">Speichern</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dein Kontostand</div>
                <div class="card-body">
                    <center><h1>{{ number_format($company->balance / 100, 2, ',', '.') }} €</h1></center>
                    <p>Nach Ablauf des kostenlosen Testmonats buchen wir den von dir gewünschten Preis (siehe links) einmal im Monat von deinem Rechnungspilot-Konto ab. Wir informieren dich rechtzeitig unter deiner E-Mail-Adresse ({{ $company->email }}), wenn es Zeit wird, dein Rechnungspilot-Konto aufzuladen – wenn du ihn dann weiterhin betreiben möchtest. </p>
                    @if(is_null($company->charging_next_at))
                        <div>Du nutzt Rechnungspilot.de kostenlos</div>
                    @else
                        <div><b>Nächste Abbuchung:</b> {{ $company->charging_next_at->format('d.m.Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-3">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Konto aufladen</div>
                <div class="card-body">
                    <p>Überweise einfach Guthaben in gewünschter Höhe auf des Rechungspilot-Konto:</p>
                    <div class="row">
                        <div class="col-md-4"><b>Kontoinhaber</b></div>
                        <div class="col-md-8">Daniel Sundermeier</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>IBAN</b></div>
                        <div class="col-md-8">DE91 7012 0400 8492 4350 14</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Institut</b></div>
                        <div class="col-md-8">BNP Paribas Niederlassung Deutschland</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>BIC</b></div>
                        <div class="col-md-8">DABBDEMMXXX</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Verwendungszweck</b></div>
                        <div class="col-md-8">Rechnungspilot {{ $company->id }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Buchungen</div>
                <div class="card-body">
                    <transaction-company-table :company="{{ json_encode($company) }}"></transaction-company-table>
                </div>
            </div>
        </div>

    </div>

@endsection