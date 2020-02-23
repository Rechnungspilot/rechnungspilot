@extends('layouts.layout')

@section('title', $quote->typeName . ' > ' . $quote->name)

@section('content')
    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url($quote->path) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
            <a href="{{ url('/angebote') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Kontakt</b></div>
                        <div class="col-md-8">{{ $quote->contact->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Fällig</b></div>
                        <div class="col-md-8">{{ $quote->dateDueForHumans }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Betrag netto</b></div>
                        <div class="col-md-8">{{ number_format($quote->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>USt.</b></div>
                        <div class="col-md-8">{{ number_format($quote->tax_value / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag brutto</b></div>
                        <div class="col-md-8">{{ number_format($quote->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body container">
            <object data="/belege/vorlage/{{ $quote->id }}" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $quote->statuses])
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <comments uri="/angebote" :item="{{ json_encode($quote) }}"></comments>
        </div>
    </div>

@endsection