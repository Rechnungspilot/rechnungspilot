@extends('layouts.layout')

@section('title', $company->name)

@section('content')

    <form action="{{ url('/firma', $company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-header">Kontakt</div>
            <div class="card-body">

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $company->name }}">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">


                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="lastname">Nachname</label>
                            <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $company->lastname }}">
                            @if ($errors->has('lastname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('lastname') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="firstname">Vorname</label>
                            <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $company->firstname }}">
                            @if ($errors->has('firstname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('firstname') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="address">Straße</label>
                            <input type="text" class="form-control {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $company->address }}">
                            @if ($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col-2">

                        <div class="form-group">
                            <label for="postcode">PLZ</label>
                            <input type="text" class="form-control {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $company->postcode }}">
                            @if ($errors->has('postcode'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('postcode') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="city">Stadt</label>
                            <input type="text" class="form-control {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $company->city }}">
                            @if ($errors->has('city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('city') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="phonenumber">Telefon</label>
                            <input type="text" class="form-control {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $company->phonenumber }}">
                            @if ($errors->has('phonenumber'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phonenumber') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="faxnumber">Fax</label>
                            <input type="text" class="form-control {{ ($errors->has('faxnumber') ? 'is-invalid' : '') }}" id="faxnumber" name="faxnumber" value="{{ old('faxnumber') ?? $company->faxnumber }}">
                            @if ($errors->has('faxnumber'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('faxnumber') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="text" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $company->email }}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="web">Web</label>
                            <input type="text" class="form-control {{ ($errors->has('web') ? 'is-invalid' : '') }}" id="web" name="web" value="{{ old('web') ?? $company->web }}">
                            @if ($errors->has('web'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('web') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Bankverbindung</div>
            <div class="card-body">
                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="bankname">Bank</label>
                            <input type="text" class="form-control {{ ($errors->has('bankname') ? 'is-invalid' : '') }}" id="bankname" name="bankname" value="{{ old('bankname') ?? $company->bankname }}">
                            @if ($errors->has('bankname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bankname') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="accountholdername">Kontoinhaber</label>
                            <input type="text" class="form-control {{ ($errors->has('accountholdername') ? 'is-invalid' : '') }}" id="accountholdername" name="accountholdername" value="{{ old('accountholdername') ?? $company->accountholdername }}">
                            @if ($errors->has('accountholdername'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('accountholdername') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="bic">BIC</label>
                            <input type="text" class="form-control {{ ($errors->has('bic') ? 'is-invalid' : '') }}" id="bic" name="bic" value="{{ old('bic') ?? $company->bic }}">
                            @if ($errors->has('bic'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bic') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="iban">IBAN</label>
                            <input type="text" class="form-control {{ ($errors->has('iban') ? 'is-invalid' : '') }}" id="iban" name="iban" value="{{ old('iban') ?? $company->iban }}">
                            @if ($errors->has('iban'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('iban') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Sonstiges</div>
            <div class="card-body">
                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="districtcourt">Amtsgericht</label>
                            <input type="text" class="form-control {{ ($errors->has('districtcourt') ? 'is-invalid' : '') }}" id="districtcourt" name="districtcourt" value="{{ old('districtcourt') ?? $company->districtcourt }}">
                            @if ($errors->has('districtcourt'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('districtcourt') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="traderegister">Handelsregister-Nr.</label>
                            <input type="text" class="form-control {{ ($errors->has('traderegister') ? 'is-invalid' : '') }}" id="traderegister" name="traderegister" value="{{ old('traderegister') ?? $company->traderegister }}">
                            @if ($errors->has('traderegister'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('traderegister') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <div class="col">

                        <div class="form-group">
                            <label for="euvatnumber">USt.-IdNr.</label>
                            <input type="text" class="form-control {{ ($errors->has('euvatnumber') ? 'is-invalid' : '') }}" id="euvatnumber" name="euvatnumber" value="{{ old('euvatnumber') ?? $company->euvatnumber }}">
                            @if ($errors->has('euvatnumber'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('euvatnumber') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col">

                        <div class="form-group">
                            <label for="vatnumber">Steuernummer</label>
                            <input type="text" class="form-control {{ ($errors->has('vatnumber') ? 'is-invalid' : '') }}" id="vatnumber" name="vatnumber" value="{{ old('vatnumber') ?? $company->vatnumber }}">
                            @if ($errors->has('vatnumber'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vatnumber') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>

@endsection