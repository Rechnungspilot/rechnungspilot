@extends('layouts.layout')

@section('title', 'Aufträge > ' . $order->name . ' > ' . $appointment->name)

@section('content')
    <div class="row">
        <div class="col">

        </div>
        <div class="col-md-auto d-flex">
            <form action="{{ url('/aufgaben/' . $appointment->id . '/erledigt') }}" method="POST">
                @csrf
                <input type="hidden" name="completed" value="<?php echo ($appointment->completed ? '0' : '1'); ?>">
                <button type="submit" class="btn btn-secondary pointer mr-1"><?php echo ($appointment->completed ? 'Nicht erledigt' : 'Erledigt'); ?></button>
            </form>
            <a href="{{ url('/auftraege/' . $order->id . '/edit') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ url('/auftraege/' . $order->id . '/termine/' . $appointment->id) }}" method="POST" class="mb-3">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="start_at">Datum</label>
            <datetime-input name="start_at" value="{{ $appointment->start_at }}" error="{{ ($errors->has('start_at') ? $errors->first('start_at') : '') }}"></datetime-input>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $appointment->name }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="user_id">Mitarbeiter</label>
            <select class="form-control {{ ($errors->has('user_id') ? 'is-invalid' : '') }}" id="user_id" name="user_id">
                @foreach($users as $user)
                    <option value="{{ $user->id}}" {{ $appointment->user_id == $user->id ? 'selected="selected"' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('user_id') }}
                </div>
            @endif
        </div>

        <address-select selected-address="{{ $appointment->contacts->first()->address }}" :selected-contact-id="{{ $appointment->contacts->first()->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>

        <div class="form-group">
            <label for="description">Beschreibung</label>
            <textarea class="form-control {{ ($errors->has('description') ? 'is-invalid' : '') }}" id="description" name="description" rows="3">{{ $appointment->description }}</textarea>
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="note">Notiz</label>
            <textarea class="form-control {{ ($errors->has('note') ? 'is-invalid' : '') }}" id="note" name="note" rows="3">{{ $appointment->note }}</textarea>
            @if ($errors->has('note'))
                <div class="invalid-feedback">
                    {{ $errors->first('note') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

    <comments uri="/aufgaben" :item="{{ json_encode($appointment) }}"></comments>

@endsection