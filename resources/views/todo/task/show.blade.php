@extends('layouts.layout')

@section('title', 'Aufgaben > ' . $todo->name)

@section('content')

    <todo-show :model="{{ json_encode($todo) }}"></todo-show>

@endsection