@extends('layouts.layout')

@section('title', ucfirst($type) . ' > Tags')

@section('content')
    <div class="row mb-3">
        <div class="col">

        </div>
        <div class="col-md-auto">
            <a href="{{ url('/' . $type) }}" class="btn btn-secondary">Übersicht</a>
        </div>

    </div>

    <tag-table slug="{{ $type }}"></tag-table>

@endsection