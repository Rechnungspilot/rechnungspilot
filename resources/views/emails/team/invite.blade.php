@component('mail::message')
Du wurdest von {{ $sender->firstname }} {{ $sender->lastname }} eingeladen dich der Firma {{ $sender->company->name }} anzuschließen.

@component('mail::button', ['url' => $url])
Einladung annehmen
@endcomponent

Der Link ist 24 Stunden lang gültig.

@endcomponent
