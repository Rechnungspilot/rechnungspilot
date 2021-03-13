@extends('layouts.layout')

@section('title', 'Abos')

@section('content')

    <a href="{{ \App\Receipts\Abos\Abo::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <receipt-table index-path="{{ \App\Receipts\Abos\Abo::indexPath(['settings_type' => $type]) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection