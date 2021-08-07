@extends('layouts.layout')

@section('title', 'Bestellung > ' . $model->name)

@section('buttons')
    <a href="{{ $model->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    <a href="{{ route('receipts.sales.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    {{ $model->address }}
                </div>

                <div class="col-md-6">

                </div>

            </div>
        </div>
    </div>

    <receipt-sales-items-table index-path="{{ \App\Receipts\Item::indexPath(['receipt_id' => $model->id]) }}" model-path="{{ route('receipts.sales.show', ['sale' => $model->id]) }}" :model="{{ json_encode($model) }}" :options="{{ json_encode($items) }}"></receipt-sales-items-table>

    <div class="card mt-5  mb-3">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $model->statuses])
        </div>
    </div>

    <div class="cardmb-3">
        <div class="card-body">
            <comments uri="/rechnungen" :item="{{ json_encode($model) }}"></comments>
        </div>
    </div>

@endsection