@extends('layouts.layout')

@section('title', 'Artikel')

@section('content')

    <a href="{{ $units_path }}" class="btn btn-secondary btn-sm">Einheiten</a>
    <a href="{{ url('/felder/artikel') }}" class="btn btn-secondary btn-sm">Individuelle Felder</a>
    <a href="{{ url('/kategorien/artikel') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/import/artikel') }}" class="btn btn-secondary btn-sm">Import</a>
    <br /><br />

    <item-table :tags="{{ json_encode($tags) }}" :types="{{ json_encode($types) }}"></item-table>

@endsection