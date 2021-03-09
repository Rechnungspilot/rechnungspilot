@extends('layouts.home')

@section('content')

@if (session('status'))
    <div class="alert alert-success mb-0" role="alert">
        <div class="container">
            {{ session('status.text') }}
        </div>
    </div>
@endif

<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <h1 class="display-6">Rechnungspilot.de</h1>
                <p class="lead">Für Freelancer, Gründer und Kleinunternehmer</p>
                <p>Professionelle Rechnungen schreiben, Kontakte verwalten und Überblick über deine Finanzen behalten – alles mit deinem Rechnungsprogramm.</p>
                <a class="btn btn-primary btn-lg" href="#contact" role="button">Nimm jetzt Kontakt auf</a>
            </div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center"><img class="img-fluid" src="{{ Storage::url('screenshot-start.png') }}"></div>
        </div>
    </div>
</div>
<div class="container">

    <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Funktionen</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Alles, was Du zur Verwaltung brauchst ist schon vorhanden: Artikel, Kontakte, Ansprechpartner, Dateien, Team, Rechnungen, Abos, Ausgaben, Mahnungen & Aufgaben</p>
                <p>Wir entwickeln deine individuellen Anforderungen gemeinsam.</p>
                <div>
                    <a class="btn btn-secondary" href="#contact">Kontakt</a>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Individuelle Lösungen für dein Unternehmen</h4>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <p class="mb-0 flex-grow-1 d-flex flex-column align-items-center justify-content-center" style="font-size: 18px;">Ich möchte Dich dabei unterstützen dein Unternehmen zu verwalten und Abläufe zu optimieren.</p>
            </div>
        </div>
    </div>

    <div class="card-deck mb-3">
        <div class="card mb-4 shadow-sm">

            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <blockquote class="blockquote">
                    <p class="mb-0">Wir können ausschließlich positives berichten! Die Kommunikation und Zusammenarbeit macht große Freude und ist immer Zielführend. Ideen werden in rasanter Geschwindigkeit ausgearbeitet und hochwertig umgesetzt. Wir profitieren ungemein von der Zusammenarbeit und sind dementsprechend begeistert.</p>
                    <footer class="blockquote-footer">Brian und Phil von <a href="https://keepseven.de/" target="_blank" rel=”nofollow”><cite title="Source Title">KeepSeven</cite></a></footer>
                </blockquote>
            </div>

        </div>
    </div>

    <div class="card-deck mb-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Meine Ziele</h4>
            </div>
            <div class="card-body d-flex flex-column">
                <p>Ich möchte Dich beim Aufbau deines Unternehmens unterstützen.</p>
                <p>Unsere Zusammenarbeit und Kommunikation ist mir dabei besonders wichtig. Mir geht es um eine ehrliche und auf Vertrauen basierende Partnerschaft.</p>
                <p>Wir erarbeiten zusammen individuelle Lösungen, um deine Verwaltung so schlank wie möglich zu halten und bleiben dauerhaft in Kontakt um die Abläufe zu optimieren und anzupassen. Du sollst dich um dein Kerngeschäft kümmern können und deine Zeit nicht "Papierkram" verbingen.</p>
                <p>Ich möchte einen engen, persönlichen Kontakt aufbauen und mich wirklich mit dir über deine Erfolge freuen. Deshalb betreue ich nur eine geringe Anzahl an Kunden. Mir geht es um Qualität anstatt Quantität!</p>
                <p>Der Aufbau eines Unternehmens ist eine spannende und erfüllende Aufgabe und ich freue mich darauf Dich auf deiner Reise zu begleiten. Wenn Du das Gleiche möchtest, schreib mir eine <a href="#contact">Nachricht</a>.</p>
                <p>Wenn Du mich erst besser kennenlernen möchtest, findest Du <a href="https://d15r.de/" target="_blank">hier</a> meine persönliche Homepage mit einer Auswahl meiner Projekte.</p>
            </div>
        </div>
    </div>

    <div class="card-deck mb-3 text-center d-none" style="min-width: 220px;">
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <blockquote class="blockquote">
                    <p class="mb-0">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo</p>
                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Kunde</cite></footer>
                </blockquote>
            </div>
            <div class="d-flex align-items-center justify-content-center mb-5">
                <img class="img-responsive rounded-circle" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" width="150">
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <blockquote class="blockquote">
                    <p class="mb-0">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo</p>
                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Kunde</cite></footer>
                </blockquote>
            </div>
            <div class="d-flex align-items-center justify-content-center mb-5">
                <img class="img-responsive rounded-circle" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" width="150">
            </div>
        </div>
    </div>

    <div class="card-deck mb-3 text-center" style="min-width: 220px;">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Preis</h4>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <span class="display-4">100 € / Monat</span>
                <small>Keine Vertragslaufzeit, Kündigungsfrist oder versteckten Kosten</small>
                <p>Fängst Du gerade erst an? Schreib mir, wir finden eine Lösung!</p>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Was ich dir biete</h4>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <p>Persönlicher Kontakt per Telefon, Videokonferenz, E-Mail, ...</p>
                <p>Individuelle Lösungen für deine Anforderungen</p>
                <p>Kurze Dienstwege & kurzfristige Umsetzung</p>
            </div>
        </div>
    </div>

    <h2>Kontakt</h2>
    <div class="card" id="contact">
        <form action="contact" method="POST">
            @csrf

            <div class="card-header">Kontakt</div>
            <div class="card-body">

                <p class="font-weight-bold">Wenn Du an einer Zusammenarbeit interessiert bist oder weitere Fragen hast, schreib mir! Ich richte Dir auch gerne einen Testzugang ein, damit Du einen Eindruck bekommen kannst.</p>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" placeholder="Name">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="name">E-Mail</label>
                    <input type="email" class="form-control {{ ($errors->has('mail') ? 'is-invalid' : '') }}" id="mail" name="mail" placeholder="E-Mail">
                    @if ($errors->has('mail'))
                        <div class="invalid-feedback">
                            {{ $errors->first('mail') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="name">Nachricht</label>
                    <textarea class="form-control {{ ($errors->has('message') ? 'is-invalid' : '') }}" id="message" name="message" rows="7"></textarea>
                    @if ($errors->has('message'))
                        <div class="invalid-feedback">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Abschicken</button>
            </div>
        </form>
    </div>

</div>
@endsection