@extends('layouts.layout')

@section('title')
    <a href="{{ \App\Item::indexPath() }}" class="text-body">Artikel</a> > Einheiten
@endsection

@section('buttons')
    <a href="{{ \App\Item::indexPath() }}" class="btn btn-secondary btn-sm">Übersicht</a>
@endsection

@section('content')

    <items-units-table index-path="{{ \App\Unit::indexPath() }}"></items-units-table>

@endsection