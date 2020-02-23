@extends('layouts.layout')

@section('title', 'Buchungen')

@section('content')

    <a href="{{ url('/kategorien/buchungen') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/konten') }}" class="btn btn-secondary btn-sm">Konten</a>
    <br /><br />
    <transaction-table :accounts="{{ json_encode($accounts) }}" :tags="{{ json_encode($tags) }}"></transaction-table>

@endsection