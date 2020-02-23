@extends('layouts.layout')

@section('title', ucfirst($type) . ' > Individuelle Felder')

@section('content')
    <div class="row">
        <div class="col">

        </div>
        <div class="col-md-auto">
            <a href="{{ url('/' . $type) }}" class="btn btn-secondary">Übersicht</a>
        </div>

    </div>

    <customfield-table for="{{ $type }}" :input-types="{{ json_encode($inputTypes) }}"></customfield-table>

@endsection