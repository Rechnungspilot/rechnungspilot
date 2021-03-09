@extends('layouts.layout')

@section('title', $person->label() . ' > ' . $person->name)

@section('buttons')
    @if ($person->default_quote == 0)
        <form action="{{ $person->path . '/default/quote' }}" class="ml-1" method="POST">
            @csrf

            <button type="submit" class="btn btn-secondary btn-sm pointer">Standard Angebot</button>
        </form>
    @else
        <form action="{{ $person->path . '/default/quote' }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm pointer">Kein Standard Angebot</button>
        </form>
    @endif
    @if ($person->default_invoice == 0)
        <form action="{{ $person->path . '/default/invoice' }}" class="ml-1" method="POST">
            @csrf

            <button type="submit" class="btn btn-secondary btn-sm pointer">Standard Rechnung</button>
        </form>
    @else
        <form action="{{ $person->path . '/default/invoice' }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm pointer">Kein Standard Rechnung</button>
        </form>
    @endif
    <a href="{{ $contact->path }}" class="btn btn-secondary btn-sm btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    <form action="{{ $person->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="title">Anrede</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') ?? $person->title }}">
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="lastname">Nachname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') ?? $person->lastname }}">
                                @error('lastname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="firstname">Vorname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname') ?? $person->firstname }}">
                                @error('firstname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="phonenumber">Telefon</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('phonenumber') is-invalid @enderror" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $person->phonenumber }}">
                                @error('phonenumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="mobilenumber">Mobil</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('mobilenumber') is-invalid @enderror" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $person->mobilenumber }}">
                                @error('mobilenumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="email">E-Mail</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?? $person->email }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="function">Funktion</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('function') is-invalid @enderror" id="function" name="function" value="{{ old('function') ?? $person->function }}">
                                @error('function')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

@endsection