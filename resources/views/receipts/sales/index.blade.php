@extends('layouts.layout')

@section('title', 'Bestellungen')

@section('buttons')

    <a href="{{ route('receipts.sales.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i></a>

@endsection

@section('content')

    <receipt-sales-table
        index-path="{{ route('receipts.sales.index') }}"
    ></receipt-sales-table>

@endsection