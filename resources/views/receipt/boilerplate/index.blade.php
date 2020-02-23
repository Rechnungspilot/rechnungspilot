@extends('layouts.layout')

@section('title', 'Textbausteine')

@section('content')
    <div class="row">
        <div class="col">

        </div>
        <div class="col-md-auto">

        </div>

    </div>

    <boilerplate-table :standards="{{ json_encode($standards) }}" :placeholder="{{ json_encode($placeholder) }}"></boilerplate-table>

@endsection