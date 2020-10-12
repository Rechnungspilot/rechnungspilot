@extends('layouts.layout')

@section('title', ucfirst($type) . ' > Tags')

@section('buttons')
    <a href="{{ url('/' . $type) }}" class="btn btn-secondary">Übersicht</a>
@endsection

@section('content')

    <tag-table slug="{{ $type }}"></tag-table>

@endsection