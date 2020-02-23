@extends('layouts.layout')

@section('title', 'Zeiten')

@section('content')

    <time-table :tags="{{ json_encode($tags) }}" :team="{{ json_encode($team) }}"></time-table>

@endsection