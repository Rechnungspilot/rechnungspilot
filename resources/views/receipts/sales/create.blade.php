@extends('layouts.layout')

@section('title', 'Bestellung anlegen')

@section('buttons')

    <a href="{{ route('receipts.sales.index') }}" class="btn btn-primary btn-sm">Übersicht</a>

@endsection

@section('content')

    <invoice-create index-path="{{ route('receipts.sales.index') }}" contact-index-path="{{ \App\Contacts\Contact::indexPath() }}" invoice-index-path="{{ \App\Receipts\Invoice::indexPath() }}" :tags="{{ json_encode($tags) }}"></invoice-create>

@endsection