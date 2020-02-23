@extends('layouts.layout')

@section('title', 'Eintellungen > Mahnstufen')

@section('content')
    <div class="row text-right">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url('/einstellungen/mahnstufen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ url( $level->path ) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="level">Mahnstufe</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="level" name="level" value="{{ $level->level }}">
            @if ($errors->has('level'))
                <div class="invalid-feedback">
                    {{ $errors->first('level') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="item_id">Position</label>
            <select class="form-control {{ ($errors->has('item_id') ? 'is-invalid' : '') }}" id="item_id" name="item_id">
                <option value="0">Keine Position erzeugen</option>
                @foreach($items as $item)
                    <option value="{{ $item->id}}" {{ $level->item_id == $item->id ? 'selected="selected"' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('item_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('item_id') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="amount">Preis</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="amount" name="amount" value="{{ number_format($level->amount / 100, 2, ',', '') }}">
            @if ($errors->has('amount'))
                <div class="invalid-feedback">
                    {{ $errors->first('amount') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>

@endsection