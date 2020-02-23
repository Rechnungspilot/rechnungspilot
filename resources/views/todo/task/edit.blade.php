@extends('layouts.layout')

@section('title', 'Aufgaben > ' . $todo->name)

@section('content')
    <div class="row">
        <div class="col">

        </div>
        <div class="col-md-auto d-flex">
            <a href="{{ url( $todo->path ) }}" class="btn btn-secondary">Aufgabe</a>
        </div>
    </div>

    <todo-edit :model="{{ json_encode($todo) }}" :users="{{ json_encode($users) }}"></todo-edit>

@endsection