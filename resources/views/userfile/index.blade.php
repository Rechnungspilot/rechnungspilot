@extends('layouts.layout')

@section('title', \App\Userfile::label())

@section('content')
    <a href="{{ \App\Userfile::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <br /><br />

    <userfile-table index-path="{{ \App\Userfile::indexPath() }}" token="{{ csrf_token() }}" :types="{{ json_encode(App\Userfile::TYPES) }}" :tags="{{ json_encode($tags) }}"></userfile-table>
@endsection