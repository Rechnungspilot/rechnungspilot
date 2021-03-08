@extends('layouts.layout')

@section('title', 'Team')

@section('content')

    <a href="{{ \App\User::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/zugriffsrollen') }}" class="btn btn-secondary btn-sm">Zugriffsrollen</a>
    <br /><br />

    <user-table index-path="{{ \App\User::indexPath() }}" :tags="{{ json_encode($tags) }}"></user-table>

@endsection