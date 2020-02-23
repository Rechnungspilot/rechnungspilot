@component('mail::message')
# Introduction

Es gibt {{ count($transactions) }} neue Buchungen:

@component('mail::table')
| Name      | Typ       | Betrag    |
|--------   |--------   |--------   |
@foreach ($transactions as $transaction)
| {{ $transaction->name }}  | {{ $transaction->type }}  |   {{ number_format($transaction->amount/100,2 , ',', '.') }}  |
@endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
