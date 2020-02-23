@extends('layouts.layout')

@section('title', ucfirst($type))

@section('content')

    <payment-table
        :accounts="{{ json_encode($accounts) }}"
        :labels="{{ json_encode($labels) }}"
        :contacts="{{ json_encode($contacts) }}"
        :statuses="{{ json_encode($statuses) }}"
        type="{{ $type }}"
    ></payment-table>

@endsection