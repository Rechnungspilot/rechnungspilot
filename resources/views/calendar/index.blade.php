@extends('layouts.layout')

@section('title', 'Kalender')

@section('content')

    <calendar :users="{{ json_encode($users) }}"></calendar>

@endsection