@extends('layouts.time')

@section('content')
<div class="container">
    <h1>Zeiterfassung</h1>
    <time-show user-id="{{ auth()->user()->id }}" :running-time="{{ !is_null($runningTime) ? json_encode($runningTime) : 'null' }}"></time-show>
</div>
@endsection