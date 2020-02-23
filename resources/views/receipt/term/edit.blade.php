@extends('layouts.layout')

@section('title', $name . ' > ' . $term->name)

@section('content')
    <div class="row">
        <div class="col">
            @if ($term->default == 0)
                <form action="{{ url('/terms/' . $type . '/' . $term->id . '/default') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-secondary pointer">Standard</button>
                </form>
            @endif
        </div>
        <div class="col-md-auto">
            <a href="{{ url('/terms/' . $type) }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ url('/terms', $term->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $term->name }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="days">Tage</label>
            <input type="text" class="form-control {{ ($errors->has('days') ? 'is-invalid' : '') }}" id="days" name="days" value="{{ $term->days }}">
            @if ($errors->has('days'))
                <div class="invalid-feedback">
                    {{ $errors->first('days') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="text">Text</label>
            <textarea class="form-control {{ ($errors->has('text') ? 'is-invalid' : '') }}" id="text" name="text" rows="5">{{ $term->text }}</textarea>
            @if ($errors->has('text'))
                <div class="invalid-feedback">
                    {{ $errors->first('text') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

@endsection