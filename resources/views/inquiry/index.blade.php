@extends('layouts.layout')

@section('title', 'Anfragen')

@section('content')
    <a href="{{ url('/kategorien/anfragen') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <letter-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></letter-table>

@endsection