@extends('layouts.layout')

@section('title', 'Ausgaben')

@section('content')
    <a class="btn btn-secondary btn-sm" href="{{ \App\Receipts\Abos\Abo::indexPath(['settings_type' => \App\Receipts\Expense::TYPE]) }}">{{ \App\Receipts\Abos\Abo::label() }}</a>
    <a href="{{ \App\Receipts\Expense::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/terms/ausgaben') }}" class="btn btn-secondary btn-sm">Zahlungsbedingungen</a>
    <br /><br />
    <receipt-table index-path="{{ \App\Receipts\Expense::indexPath() }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection