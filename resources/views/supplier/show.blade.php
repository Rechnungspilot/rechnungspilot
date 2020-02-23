@extends('layouts.layout')

@section('title', 'Lieferanten > ' . $supplier->name)

@section('content')

    <div class="push-right">
        <a href="{{ url('/lieferanten') }}" class="btn btn-secondary">Übersicht</a>&nbsp;
        <form action="{{ url('/lieferanten', $supplier->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">Löschen</button>
        </form>
    </div>

    <form action="{{ url('/lieferanten', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="number">Lieferntennummer</label>
            <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ old('number') ?? $supplier->number }}">
            @if ($errors->has('number'))
                <div class="invalid-feedback">
                    {{ $errors->first('number') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="company">Firma</label>
            <input type="text" class="form-control {{ ($errors->has('company') ? 'is-invalid' : '') }}" id="company" name="company" value="{{ old('company') ?? $supplier->company }}">
            @if ($errors->has('company'))
                <div class="invalid-feedback">
                    {{ $errors->first('company') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $supplier->lastname }}">
            @if ($errors->has('lastname'))
                <div class="invalid-feedback">
                    {{ $errors->first('lastname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $supplier->firstname }}">
            @if ($errors->has('firstname'))
                <div class="invalid-feedback">
                    {{ $errors->first('firstname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="address">Straße</label>
            <input type="text" class="form-control {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $supplier->address }}">
            @if ($errors->has('address'))
                <div class="invalid-feedback">
                    {{ $errors->first('address') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="postcode">PLZ</label>
            <input type="text" class="form-control {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $supplier->postcode }}">
            @if ($errors->has('postcode'))
                <div class="invalid-feedback">
                    {{ $errors->first('postcode') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="city">Stadt</label>
            <input type="text" class="form-control {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $supplier->city }}">
            @if ($errors->has('city'))
                <div class="invalid-feedback">
                    {{ $errors->first('city') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="text" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $supplier->email }}">
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>

@endsection