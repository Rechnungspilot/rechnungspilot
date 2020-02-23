@extends('layouts.layout')

@section('title', 'Aufgaben')

@section('content')
    <a href="{{ url('/kategorien/aufgaben') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <task-table :filter-tags="{{ json_encode($tags) }}" :filter-team="{{ json_encode($team) }}"></task-table>

@endsection