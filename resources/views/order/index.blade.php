@extends('layouts.layout')

@section('title', 'Aufträge')

@section('content')
    <a href="{{ url('/felder/auftraege') }}" class="btn btn-secondary btn-sm">Individuelle Felder</a>
    <a href="{{ url('/kategorien/auftraege') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <receipt-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection