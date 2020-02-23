@component('mail::message')
# Neuer Nutzer

Ein neuer Nutzer hat sich mit der E-Mail {{ $user->email }} registriert.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
