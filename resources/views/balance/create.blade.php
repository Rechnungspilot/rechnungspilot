@extends('layouts.layout')

@section('title', 'Guthaben > Import')

@section('content')

    <balance-create-table :companies="{{ json_encode($companies) }}" :transactions="{{ json_encode($transactions) }}"></balance-create-table>

@endsection