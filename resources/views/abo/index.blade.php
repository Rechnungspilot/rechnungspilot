@extends('layouts.layout')

@section('title', 'Abos')

@section('content')
    <a href="{{ url('/kategorien/abos') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <receipt-table :labels="{{ json_encode($labels) }}" :contacts="{{ json_encode($contacts) }}" :statuses="{{ json_encode($statuses) }}" :tags="{{ json_encode($tags) }}"></receipt-table>

@endsection