@extends('layouts.layout')

@section('title', $invoice->typeName . ' > ' . $invoice->name)

@section('content')
    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url($invoice->path) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
            <a href="{{ url('/rechnungen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Status</b></div>
                        <div class="col-md-8">{{ $invoice->status->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Kontakt</b></div>
                        <div class="col-md-8">{{ $invoice->contact->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Fällig</b></div>
                        <div class="col-md-8">{{ $invoice->dateDueForHumans }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Offen</b></div>
                        <div class="col-md-8">{{ number_format($invoice->outstandingBalance / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag netto</b></div>
                        <div class="col-md-8">{{ number_format($invoice->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>USt.</b></div>
                        <div class="col-md-8">{{ number_format($invoice->tax_value / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag brutto</b></div>
                        <div class="col-md-8">{{ number_format($invoice->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body container">
            <object data="/belege/vorlage/{{ $invoice->id }}" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $invoice->statuses])
        </div>
    </div>

    @if(count($invoice->duns))
        <div class="card mb-5">
            <div class="card-header">Mahnungen</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach($invoice->duns as $dun)
                            <tr>
                                <td>{{ $dun->date->format('d.m.Y') }}</td>
                                <td>{{ $dun->settings->level->name }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="card mb-5">
        <div class="card-body">
            <comments uri="/rechnungen" :item="{{ json_encode($invoice) }}"></comments>
        </div>
    </div>

@endsection