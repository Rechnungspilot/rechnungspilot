@extends('layouts.layout')

@section('title', ucfirst($type) . ' > Individuelle Felder')

@section('buttons')
    <a href="{{ url('/' . $type) }}" class="btn btn-secondary">Übersicht</a>
@endsection

@section('content')

    <customfield-table for="{{ $type }}" :input-types="{{ json_encode($inputTypes) }}"></customfield-table>

@endsection