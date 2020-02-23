@extends('layouts.layout')

@section('title', 'Briefe')

@section('content')
    <a href="{{ url('/kategorien/briefe') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <letter-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></letter-table>

@endsection