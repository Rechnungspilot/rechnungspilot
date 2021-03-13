@extends('layouts.layout')

@section('title', $invoice->typeName . ' > ' . $invoice->name)

@section('buttons')
    <a href="{{ $invoice->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    <div class="dropdown ml-1">
        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i> Mehr
        </button>
        <div class="dropdown-menu">
            <h6 class="dropdown-header">Anlegen</h6>
            <form action="{{ url('/rechnungen/aus', $invoice->id) }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item pointer">Duplizieren</button>
            </form>
            <form action="{{ url('/rechnungen/aus', $invoice->id) }}" method="POST">
                @csrf
                <input type="hidden" name="credit" value="1">
                <button type="submit" class="dropdown-item pointer">Gutschrift erstellen</button>
            </form>
            <h6 class="dropdown-header">Bearbeiten</h6>
            <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
        </div>
    </div>
    <a href="{{ $invoice->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if($invoice->isDeletable())
        <form action="{{ $invoice->path }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-fw fa-trash"></i></button>
        </form>
    @endif
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Status</b></div>
                        <div class="col-value">{{ $invoice->status->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Kontakt</b></div>
                        <div class="col-value">{{ $invoice->contact->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Fällig</b></div>
                        <div class="col-value">{{ $invoice->dateDueForHumans }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Offen</b></div>
                        <div class="col-value">{{ number_format($invoice->outstandingBalance / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Betrag netto</b></div>
                        <div class="col-value">{{ number_format($invoice->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>USt.</b></div>
                        <div class="col-value">{{ number_format($invoice->tax_value / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Betrag brutto</b></div>
                        <div class="col-value">{{ number_format($invoice->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body container">
            <object data="/belege/vorlage/{{ $invoice->id }}" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $invoice->statuses])
        </div>
    </div>

    @if(count($invoice->duns))
        <div class="card mb-3">
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

    <div class="card mb-3">
        <div class="card-body">
            <comments uri="/rechnungen" :item="{{ json_encode($invoice) }}"></comments>
        </div>
    </div>

@endsection