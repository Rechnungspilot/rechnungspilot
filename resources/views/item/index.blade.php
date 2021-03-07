@extends('layouts.layout')

@section('title', 'Artikel')

@section('content')

    <a href="{{ \App\Unit::indexPath() }}" class="btn btn-secondary btn-sm">Einheiten</a>
    <a href="{{ \App\Item::indexPathCustomfields() }}" class="btn btn-secondary btn-sm">Individuelle Felder</a>
    <a href="{{ \App\Item::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/import/artikel') }}" class="btn btn-secondary btn-sm">Import</a>
    <br /><br />

    <item-table index-path="{{ \App\Item::indexPath() }}" :tags="{{ json_encode($tags) }}" :types="{{ json_encode($types) }}"></item-table>

@endsection