@extends('layouts.layout')

@section('title', 'Angebote')

@section('content')
    <a href="{{ url('/terms/angebote') }}" class="btn btn-secondary btn-sm">Gültigkeiten</a>
    <a href="{{ url('/kategorien/angebote') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/textbausteine') }}" class="btn btn-secondary btn-sm">Textbausteine</a>
    <br /><br />
    <receipt-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection