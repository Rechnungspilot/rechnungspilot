@extends('layouts.layout')

@section('title', 'Mitarbeiter > ' . $user->name)

@section('content')

    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="d-flex col-sm col-sm-auto">
            @if(!$user->password)
                <form action="{{ url('/team/' . $user->id . '/einladen') }}" method="POST">
                    @csrf

                    <input id="email" type="hidden" class="form-control" name="email" value="{{ $user->email }}">
                    <button type="submit" class="btn btn-success">
                        Einladen
                    </button>
                </form>
            @endif
            <a class="btn btn-secondary ml-1" href="{{ url('/team') }}" title="Übersicht"><i class="fas fa-fw fa-th-list"></i></a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/team', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="number">Personalnummer</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $user->number }}">
            @if ($errors->has('number'))
                <div class="invalid-feedback">
                    {{ $errors->first('number') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ $user->lastname }}">
            @if ($errors->has('lastname'))
                <div class="invalid-feedback">
                    {{ $errors->first('lastname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ $user->firstname }}">
            @if ($errors->has('firstname'))
                <div class="invalid-feedback">
                    {{ $errors->first('firstname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="hex_color_code">Farbe</label>
            <input type="color" class="form-control {{ ($errors->has('hex_color_code') ? 'is-invalid' : '') }}" id="hex_color_code" name="hex_color_code" value="{{ $user->hex_color_code }}">
            @if ($errors->has('hex_color_code'))
                <div class="invalid-feedback">
                    {{ $errors->first('hex_color_code') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="address">Straße</label>
            <input type="text" class="form-control {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $user->address }}">
            @if ($errors->has('address'))
                <div class="invalid-feedback">
                    {{ $errors->first('address') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="postcode">PLZ</label>
            <input type="text" class="form-control {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $user->postcode }}">
            @if ($errors->has('postcode'))
                <div class="invalid-feedback">
                    {{ $errors->first('postcode') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="city">Stadt</label>
            <input type="text" class="form-control {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $user->city }}">
            @if ($errors->has('city'))
                <div class="invalid-feedback">
                    {{ $errors->first('city') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="phonenumber">Telefon</label>
            <input type="text" class="form-control {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $user->phonenumber }}">
            @if ($errors->has('phonenumber'))
                <div class="invalid-feedback">
                    {{ $errors->first('phonenumber') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="mobilenumber">Mobil</label>
            <input type="text" class="form-control {{ ($errors->has('mobilenumber') ? 'is-invalid' : '') }}" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $user->mobilenumber }}">
            @if ($errors->has('mobilenumber'))
                <div class="invalid-feedback">
                    {{ $errors->first('mobilenumber') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="text" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ $user->email }}">
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="bankname">Bank</label>
            <input type="text" class="form-control {{ ($errors->has('bankname') ? 'is-invalid' : '') }}" id="bankname" name="bankname" value="{{ old('bankname') ?? $user->bankname }}">
            @if ($errors->has('bankname'))
                <div class="invalid-feedback">
                    {{ $errors->first('bankname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="bic">BIC</label>
            <input type="text" class="form-control {{ ($errors->has('bic') ? 'is-invalid' : '') }}" id="bic" name="bic" value="{{ old('bic') ?? $user->bic }}">
            @if ($errors->has('bic'))
                <div class="invalid-feedback">
                    {{ $errors->first('bic') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="iban">IBAN</label>
            <input type="text" class="form-control {{ ($errors->has('iban') ? 'is-invalid' : '') }}" id="iban" name="iban" value="{{ old('iban') ?? $user->iban }}">
            @if ($errors->has('iban'))
                <div class="invalid-feedback">
                    {{ $errors->first('iban') }}
                </div>
            @endif
        </div>

        <h3>Zugriffsrollen</h3>
        @foreach($roles as $role)
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $role->id }}" id="role-{{ $role->id }}" name="roles[]" {{ $user->hasRole($role) ? 'checked="checked"' : '' }}>
                    <label class="form-check-label" for="role-{{ $role->id }}">
                        {{ $role->name }}
                    </label>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />
    <tag-select class="my-2" :selected="{{ json_encode($user->tags) }}" type="team" type_id="{{ $user->id }}"></tag-select>

    <userfileable-table uri="/team" :model="{{ json_encode($user) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/team" :item="{{ json_encode($user) }}"></comments>
@endsection