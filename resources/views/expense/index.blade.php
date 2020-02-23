@extends('layouts.layout')

@section('title', 'Ausgaben')

@section('content')
    <a href="{{ url('/kategorien/ausgaben') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/terms/ausgaben') }}" class="btn btn-secondary btn-sm">Zahlungsbedingungen</a>
    <br /><br />
    <receipt-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection