@extends('layouts.layout')

@section('title', $user->label() . ' > ' . $user->name)

@section('buttons')
    <a class="btn btn-secondary ml-1 btn-sm" href="{{ $user->path }}" title="Übersicht">Übersicht</a>
@endsection

@section('content')

    <div class="container-fluid">

        <form action="{{ $user->path }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-12 col-lg-6">

                    <div class="card mb-3">
                        <div class="card-header">Allgemein</div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="number">Personalnummer</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="number" name="number" value="{{ $user->number }}">
                                    @error('number')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('number') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="lastname">Nachname</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ $user->lastname }}">
                                    @error('lastname')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="firstname">Vorname</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ $user->firstname }}">
                                    @error('firstname')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="hex_color_code">Farbe</label>
                                <div class="col-sm-8">
                                    <input type="color" class="form-control form-control-sm @error('hex_color_code') is-invalid @enderror" id="hex_color_code" name="hex_color_code" value="{{ $user->hex_color_code }}">
                                    @error('hex_color_code')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('hex_color_code') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="address">Straße</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') ?? $user->address }}">
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="postcode">PLZ</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('postcode') is-invalid @enderror" id="postcode" name="postcode" value="{{ old('postcode') ?? $user->postcode }}">
                                    @error('postcode')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('postcode') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="city">Stadt</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') ?? $user->city }}">
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('city') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <tag-select class="my-2" :selected="{{ json_encode($user->tags) }}" index-path="{{ $user::indexPathTags() }}" path="{{ $user->tags_path }}"></tag-select>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-lg-6">

                    <div class="card mb-3">

                        <div class="card-header">Kontakt</div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="phonenumber">Telefon</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('phonenumber') is-invalid @enderror" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $user->phonenumber }}">
                                    @error('phonenumber')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phonenumber') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="mobilenumber">Mobil</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('mobilenumber') is-invalid @enderror" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $user->mobilenumber }}">
                                    @error('mobilenumber')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('mobilenumber') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="email">E-Mail</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card mb-3">

                        <div class="card-header">Bankverbindung</div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="bankname">Bank</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('bankname') is-invalid @enderror" id="bankname" name="bankname" value="{{ old('bankname') ?? $user->bankname }}">
                                    @error('bankname')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('bankname') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="bic">BIC</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('bic') is-invalid @enderror" id="bic" name="bic" value="{{ old('bic') ?? $user->bic }}">
                                    @error('bic')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('bic') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="iban">IBAN</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('iban') is-invalid @enderror" id="iban" name="iban" value="{{ old('iban') ?? $user->iban }}">
                                    @error('iban')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('iban') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card mb-3">
                        <div class="card-header">Zugriffsrollen</div>
                        <div class="card-body">

                            @foreach($roles as $role)
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $role->id }}" id="role-{{ $role->id }}" name="roles[]" {{ $user->hasRole($role) ? 'checked="checked"' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>

            </div>

            <div class="row my-5"><div class="col"></div></div>

            <div class="fixed-bottom bg-white p-3 text-right">
                <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
            </div>

        </form>

    </div>

@endsection