@extends('layouts.layout')

@section('title', 'Kontakte')

@section('content')

    <a href="{{ url('/felder/kontakte') }}" class="btn btn-secondary btn-sm">Individuelle Felder</a>
    <a href="{{ url('/kategorien/kontakte') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <contact-table :tags="{{ json_encode($tags) }}"></contact-table>

@endsection