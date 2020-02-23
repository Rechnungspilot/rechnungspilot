@extends('layouts.layout')

@section('title', 'Projekte > ' . $project->name)

@section('content')

    <project-show :item="{{ json_encode($project) }}"></project-show>

@endsection