@extends('layouts.layout')

@section('title', 'Mahnungen')

@section('content')
    <a href="{{ url('/kategorien/lieferscheine') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <dun-table
        :labels="{{ json_encode($labels) }}"
        :contacts="{{ json_encode($contacts) }}"
        :statuses="{{ json_encode($statuses) }}"
        :tags="{{ json_encode($tags) }}"
    ></dun-table>

@endsection