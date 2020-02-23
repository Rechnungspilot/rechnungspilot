@extends('layouts.layout')

@section('title', 'Dateien')

@section('content')
    <a href="{{ url('/kategorien/dateien') }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />
    <userfile-table uri="/dateien" token="{{ csrf_token() }}" :filter-types="{{ json_encode(App\Userfile::TYPES) }}" :filter-tags="{{ json_encode($tags) }}"></userfile-table>
@endsection