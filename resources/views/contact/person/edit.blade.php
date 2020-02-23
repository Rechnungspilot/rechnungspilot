@extends('layouts.layout')

@section('title', 'Ansprechpartner > ' . $person->name)

@section('content')
    <div class="row mb-3">
        <div class="col d-flex">
            @if ($person->default_quote == 0)
                <form action="{{ url('/kontakte/ansprechpartner/' . $person->id . '/default/quote') }}" class="mr-1" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-secondary pointer">Standard Angebot</button>
                </form>
            @else
                <form action="{{ url('/kontakte/ansprechpartner/' . $person->id . '/default/quote') }}" class="mr-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger pointer">Kein Standard Angebot</button>
                </form>
            @endif
            @if ($person->default_invoice == 0)
                <form action="{{ url('/kontakte/ansprechpartner/' . $person->id . '/default/invoice') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-secondary pointer">Standard Rechnung</button>
                </form>
            @else
                <form action="{{ url('/kontakte/ansprechpartner/' . $person->id . '/default/invoice') }}" class="mr-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger pointer">Kein Standard Rechnung</button>
                </form>
            @endif
        </div>
        <div class="col-md-auto">
            <a href="{{ url('/kontakte/' . $person->contact_id) }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ url('/kontakte/ansprechpartner', $person->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Anrede</label>
            <input type="text" class="form-control {{ ($errors->has('title') ? 'is-invalid' : '') }}" id="title" name="title" value="{{ old('title') ?? $person->title }}">
            @if ($errors->has('title'))
                <div class="invalid-feedback">
                    {{ $errors->first('title') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $person->lastname }}">
            @if ($errors->has('lastname'))
                <div class="invalid-feedback">
                    {{ $errors->first('lastname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $person->firstname }}">
            @if ($errors->has('firstname'))
                <div class="invalid-feedback">
                    {{ $errors->first('firstname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="phonenumber">Telefon</label>
            <input type="text" class="form-control {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $person->phonenumber }}">
            @if ($errors->has('phonenumber'))
                <div class="invalid-feedback">
                    {{ $errors->first('phonenumber') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="mobilenumber">Mobil</label>
            <input type="text" class="form-control {{ ($errors->has('mobilenumber') ? 'is-invalid' : '') }}" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $person->mobilenumber }}">
            @if ($errors->has('mobilenumber'))
                <div class="invalid-feedback">
                    {{ $errors->first('mobilenumber') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="text" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $person->email }}">
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="function">Funktion</label>
            <input type="text" class="form-control {{ ($errors->has('function') ? 'is-invalid' : '') }}" id="function" name="function" value="{{ old('function') ?? $person->function }}">
            @if ($errors->has('function'))
                <div class="invalid-feedback">
                    {{ $errors->first('function') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

@endsection