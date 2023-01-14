@extends('layouts.layout')

@section('title', $expense->typeName . ' > ' . $expense->name)

@section('buttons')
    <a href="{{ $expense->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    <div class="dropdown ml-1">
        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i> Mehr
        </button>
        <div class="dropdown-menu">
            <h6 class="dropdown-header">Anlegen</h6>
            <form action="{{ url('/ausgaben/aus', $expense->id) }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item pointer">Duplizieren</button>
            </form>
            <form action="{{ url('/ausgaben/aus', $expense->id) }}" method="POST">
                @csrf
                <input type="hidden" name="credit" value="1">
                <button type="submit" class="dropdown-item pointer">Gutschrift erstellen</button>
            </form>
            <h6 class="dropdown-header">Bearbeiten</h6>
            <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
        </div>
    </div>
    <a href="{{ $expense->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if($expense->isDeletable())
        <form action="{{ $expense->path }}" class="ml-1" method="POST">
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
                        <div class="col-md-4"><b>Status</b></div>
                        <div class="col-md-8">{{ $expense->status->name ?? 'Kein Status' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Kontakt</b></div>
                        <div class="col-md-8">{{ $expense->contact->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Fällig</b></div>
                        <div class="col-md-8">{{ $expense->dateDueForHumans }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Offen</b></div>
                        <div class="col-md-8">{{ number_format($expense->outstandingBalance / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag netto</b></div>
                        <div class="col-md-8">{{ number_format($expense->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>USt.</b></div>
                        <div class="col-md-8">{{ number_format($expense->tax_value / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag brutto</b></div>
                        <div class="col-md-8">{{ number_format($expense->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($expense->previewFile)
        <div class="card mb-3">
            <div class="card-body container">
                <object data="{{ $expense->previewFile->url}}" style="width: 100%; height: 600px">
                    <center>PDF kann nicht angezeigt werden.</center>
                </object>
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $expense->statuses])
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <comments uri="/rechnungen" :item="{{ json_encode($expense) }}"></comments>
        </div>
    </div>

@endsection