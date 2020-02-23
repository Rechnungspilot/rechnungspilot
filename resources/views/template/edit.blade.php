@extends('layouts.layout')

@section('title', 'Vorlagen')

@section('content')

    <template-edit :model="{{ json_encode($template) }}" :templates="{{ json_encode($templates) }}" :headers="{{ json_encode($headers) }}" token="{{ csrf_token() }}"></template-edit>

@endsection