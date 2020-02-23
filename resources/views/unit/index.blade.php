@extends('layouts.layout')

@section('title', 'Artikel > Einheiten')

@section('content')
    <div class="row">
        <div class="col">

        </div>
        <div class="col-md-auto">
            <a href="{{ url('/artikel') }}" class="btn btn-secondary">Übersicht</a>
        </div>

    </div>

    <unit-table></unit-table>

@endsection