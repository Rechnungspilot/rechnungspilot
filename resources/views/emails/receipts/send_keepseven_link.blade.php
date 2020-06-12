@component('mail::message')
# Link zum Erstellen der Rechnung

@component('mail::button', ['url' => $url])
Rechnung erstellen
@endcomponent

Der Link ist 24 Stunden lang gültig.

@endcomponent
