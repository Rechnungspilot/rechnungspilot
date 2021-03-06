@extends('layouts.layout')

@section('title')
    <a href="{{ url('/artikel') }}" class="text-body">Artikel</a> > Einheiten
@endsection

@section('buttons')
    <a href="{{ url('/artikel') }}" class="btn btn-secondary btn-sm">Übersicht</a>
@endsection

@section('content')

    <items-units-table index-path="{{ $index_path }}"></items-units-table>

@endsection