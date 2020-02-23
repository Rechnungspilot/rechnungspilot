@extends('layouts.layout')

@section('title', 'Team')

@section('content')

    <a href="{{ url('/kategorien/team') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/zugriffsrollen') }}" class="btn btn-secondary btn-sm">Zugriffsrollen</a>
    <br /><br />
    <user-table :tags="{{ json_encode($tags) }}"></user-table>

@endsection