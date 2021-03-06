@extends('layouts.layout')

@section('title', 'Rechnungen')

@section('content')
    <a class="btn btn-secondary btn-sm" href="{{ \App\Receipts\Abos\Abo::indexPath(['settings_type' => \App\Receipts\Invoice::TYPE]) }}">{{ \App\Receipts\Abos\Abo::label() }}</a>
    <a href="{{ \App\Receipts\Invoice::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/textbausteine') }}" class="btn btn-secondary btn-sm">Textbausteine</a>
    <a href="{{ url('/terms/rechnungen') }}" class="btn btn-secondary btn-sm">Zahlungsbedingungen</a>
    <a href="{{ url('/import/philip') }}" class="btn btn-secondary btn-sm">Import Philip</a>
    <br /><br />
    <receipt-table
        index-path="{{ \App\Receipts\Invoice::indexPath() }}"
        :contacts="{{ json_encode($contacts) }}"
        :statuses="{{ json_encode($statuses) }}"
        :tags="{{ json_encode($tags) }}"
    ></receipt-table>

@endsection