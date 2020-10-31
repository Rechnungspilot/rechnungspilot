@extends('layouts.layout')

@section('title', 'Start')

@section('content')

    <div class="row mb-3">

        <div class="col">

            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div>Offene Rechnungen</div>
                    <a class="text-body" href="{{ url('/forderungen') }}"><i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="text-primary font-weight-bold" style="font-size: 20px;">{{ number_format($outstanding_invoices->amount / 100, 2, ',', '.') }} €</div>
                    <div class="text-muted">{{ $outstanding_invoices->count }} {{ $outstanding_invoices->count == 1 ? 'Rechnung' : 'Rechnungen' }}</div>
                </div>
            </div>
        </div>

        <div class="col">

            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div>Offene Ausgaben</div>
                    <a class="text-body" href="{{ url('/verbindlichkeiten') }}"><i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="text-primary font-weight-bold" style="font-size: 20px;">{{ number_format($outstanding_expenses->amount / 100, 2, ',', '.') }} €</div>
                    <div class="text-muted">{{ $outstanding_expenses->count }}  {{ $outstanding_expenses->count == 1 ? 'Ausgabe' : 'Ausgaben' }}</div>
                </div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Umsatz</div>
                <div class="card-body">
                    <contact-revenue-chart></contact-revenue-chart>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">

            <div class="card mb-3">
                <div class="card-header">Neuste Kommentare</div>
                <div class="card-body">
                    <comments></comments>
                </div>
            </div>

        </div>

        <!-- <div class="col">

            <div class="card">
                <div class="card-header">Neuste Interaktionen</div>
                <div class="card-body">
                    <interaction-table></interaction-table>
                </div>
            </div>

        </div> -->

        <div class="col">

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>Aufgaben</div>
                    <a class="text-body" href="{{ url('/aufgaben') }}"><i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <task-table hide-filter="1" :team-id="1"></task-table>
                </div>
            </div>

        </div>
    </div>
@endsection