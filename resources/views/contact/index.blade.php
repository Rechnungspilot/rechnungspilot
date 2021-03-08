@extends('layouts.layout')

@section('title', \App\Contacts\Contact::label())

@section('content')

    <a href="{{ \App\Contacts\Contact::indexPathCustomfields() }}" class="btn btn-secondary btn-sm">Individuelle Felder</a>
    <a href="{{ \App\Contacts\Contact::indexPathTags() }}" class="btn btn-secondary btn-sm">Kategorien</a>
    <a href="{{ url('/import/kontakte') }}" class="btn btn-secondary btn-sm">Import</a>
    <br /><br />
    <contact-table index-path="{{ \App\Contacts\Contact::indexPath() }}" :tags="{{ json_encode($tags) }}"></contact-table>

@endsection