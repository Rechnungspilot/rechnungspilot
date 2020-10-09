@extends('layouts.layout')

@section('title', 'Rechnungen')

@section('content')
    <a class="btn btn-secondary btn-sm" href="{{ url('/abos') }}">Abos</a>
    <a href="{{ url('/kategorien/rechnungen') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/textbausteine') }}" class="btn btn-secondary btn-sm">Textbausteine</a>
    <a href="{{ url('/terms/rechnungen') }}" class="btn btn-secondary btn-sm">Zahlungsbedingungen</a>
    <a href="{{ url('/import/philip') }}" class="btn btn-secondary btn-sm">Import Philip</a>
    <br /><br />
    <receipt-table
        :labels="{{ json_encode($labels) }}"
        :contacts="{{ json_encode($contacts) }}"
        :statuses="{{ json_encode($statuses) }}"
        :tags="{{ json_encode($tags) }}"
    ></receipt-table>

@endsection