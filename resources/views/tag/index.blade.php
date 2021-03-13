@extends('layouts.layout')

@section('title', $class::label() . ' > Tags')

@section('buttons')
    <a href="{{ $class::indexPath($index_path_attributes) }}" class="btn btn-secondary btn-sm">{{ $class::label() }}</a>
@endsection

@section('content')

    <tag-table slug="{{ $type }}"></tag-table>

@endsection