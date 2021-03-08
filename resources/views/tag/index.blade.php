@extends('layouts.layout')

@section('title', $class::label() . ' > Tags')

@section('buttons')
    <a href="{{ $class::indexPath() }}" class="btn btn-secondary">{{ $class::label() }}</a>
@endsection

@section('content')

    <tag-table slug="{{ $type }}"></tag-table>

@endsection