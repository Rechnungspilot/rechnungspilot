@extends('layouts.layout')

@section('title', $name)

@section('content')
    <div class="row">
        <div class="col"></div>
        <div class="col-md-auto">
            <a href="{{ url('/' . $type) }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>
    <term-table type="{{ $type }}" name="{{ $name }}"></term-table>

@endsection