@extends('layouts.layout')

@section('title', 'Start')

@section('content')

    <div class="row mb-3">

        <div class="col">

            <div class="card h-100">
                <div class="card-header"><a class="text-body" href="{{ url('/anfragen') }}">Anfragen</a></div>
                <div class="card-body">
                    @if(count($inquiriesCount) == 0)
                        <p>Keine offenen Anfragen</p>
                    @else
                        <div class="row">
                            @foreach ($inquiriesCount as $count)
                                <div class="col">
                                    <div>
                                        <div class="text-center text-muted">{{ $count->latest_status_type::NAME }}</div>
                                    </div>
                                    <div>
                                        <div class="text-center">{{ $count->count }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col">

            <div class="card h-100">
                <div class="card-header"><a class="text-body" href="{{ url('/angebote') }}">Offene Angebote</a></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="text-muted">Betrag</div>
                            <div>{{ number_format($openQuotes->amount / 100, 2, ',', '.') }} €</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Anzahl</div>
                            <div>{{ $openQuotes->count }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col">

            <div class="card h-100">
                <div class="card-header"><a class="text-body" href="{{ url('/auftraege') }}">Aufträge</a></div>
                <div class="card-body">
                    @if(count($orderCount) == 0)
                        <p class="text-center">Keine offenen Aufträge</p>
                    @else
                        <div class="row">
                            @foreach ($orderCount as $count)
                                <div class="col">
                                    <div>
                                        <div class="text-center text-muted">{{ $count->latest_status_type::NAME }}</div>
                                    </div>
                                    <div>
                                        <div class="text-center">{{ $count->count }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col">

            <div class="card h-100">
                <div class="card-header"><a class="text-body" href="{{ url('/rechnungen') }}">Offene Rechnungen</a></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="text-muted">Betrag</div>
                            <div>{{ number_format($outstandingInvoices->amount / 100, 2, ',', '.') }} €</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Anzahl</div>
                            <div>{{ $outstandingInvoices->count }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col">

            <div class="card">
                <div class="card-header">Neuste Kommentare</div>
                <div class="card-body">
                    <comments></comments>
                </div>
            </div>

        </div>

        <div class="col">

            <div class="card">
                <div class="card-header">Neuste Interaktionen</div>
                <div class="card-body">
                    <interaction-table></interaction-table>
                </div>
            </div>

        </div>

        <div class="col">

            <div class="card">
                <div class="card-header"><a class="text-body" href="{{ url('/aufgaben') }}">Aufgaben</a></div>

                <div class="card-body">
                    <task-table hide-filter="1"></task-table>
                </div>
            </div>

        </div>
    </div>
@endsection