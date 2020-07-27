@extends('layouts.layout')

@section('title', 'Artikel > ' . $item->name)

@section('content')

    <div class="row text-right">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url($item->path) }}" class="btn btn-secondary">Artikel</a>
        </div>
    </div>

    <form action="{{ url('/artikel', $item->id) }}" class="mb-3" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="type">Typ</label>
            <select class="form-control {{ ($errors->has('type') ? 'is-invalid' : '') }}" id="type" name="type">
                @foreach ($types as $key => $name)
                    <option value="{{ $key }}" {{ $item->type == $key ? 'selected="selected"' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @if ($errors->has('type'))
                <div class="invalid-feedback">
                    {{ $errors->first('type') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="number">Artikelnummer</label>
            <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $item->number }}">
            @if ($errors->has('number'))
                <div class="invalid-feedback">
                    {{ $errors->first('number') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $item->name }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="description">Beschreibung</label>
            <textarea class="form-control {{ ($errors->has('description') ? 'is-invalid' : '') }}" id="description" name="description" rows="3">{{ $item->description }}</textarea>
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="unit_price">Preis/Einheit</label>
            <input type="text" class="form-control {{ ($errors->has('unit_price') ? 'is-invalid' : '') }}" id="unit_price" name="unit_price" value="{{ number_format($item->unit_price, $item->decimals, ',', '') }}">
            @if ($errors->has('unit_price'))
                <div class="invalid-feedback">
                    {{ $errors->first('unit_price') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="decimals">Nachkommastellen</label>
            <select class="form-control {{ ($errors->has('decimals') ? 'is-invalid' : '') }}" id="decimals" name="decimals">
                @foreach($decimals as $decimal)
                    <option value="{{ $decimal }}" {{ $item->decimals == $decimal ? 'selected="selected"' : '' }}>{{ $decimal}}</option>
                @endforeach
            </select>
            @if ($errors->has('decimals'))
                <div class="invalid-feedback">
                    {{ $errors->first('decimals') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="unit_id">Einheit</label>
            <select class="form-control {{ ($errors->has('unit_id') ? 'is-invalid' : '') }}" id="unit_id" name="unit_id">
                @foreach($units as $unit)
                    <option value="{{ $unit->id}}" {{ $item->unit->id == $unit->id ? 'selected="selected"' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                @endforeach
            </select>
            @if ($errors->has('unit_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('unit_id') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="tax">USt.</label>
            <select class="form-control {{ ($errors->has('tax') ? 'is-invalid' : '') }}" id="tax" name="tax">
                <option value="0.19" {{ $item->tax == 0.19 ? 'selected="selected"' : '' }}>19%</option>
                <option value="0.16" {{ $item->tax == 0.16 ? 'selected="selected"' : '' }}>16%</option>
                <option value="0.07" {{ $item->tax == 0.07 ? 'selected="selected"' : '' }}>7%</option>
                <option value="0" {{ $item->tax == 0 ? 'selected="selected"' : '' }}>0%</option>
            </select>
            @if ($errors->has('tax'))
                <div class="invalid-feedback">
                    {{ $errors->first('tax') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="unit_cost">Einkaufspreis</label>
            <input type="text" class="form-control {{ ($errors->has('unit_cost') ? 'is-invalid' : '') }}" id="unit_cost" name="unit_cost" value="{{ number_format($item->unit_cost, $item->decimals, ',', '') }}">
            @if ($errors->has('unit_cost'))
                <div class="invalid-feedback">
                    {{ $errors->first('unit_cost') }}
                </div>
            @endif
        </div>

        <div class="form-row align-items-end">

            <div class="col-auto form-group">
                <label for="duration_hour">Dauer</label>
                <input type="text" class="form-control {{ ($errors->has('duration_hour') ? 'is-invalid' : '') }}" id="duration_hour" name="duration_hour" value="{{ number_format($item->durationHour, 0, ',', '') }}">
                @if ($errors->has('duration_hour'))
                    <div class="invalid-feedback">
                        {{ $errors->first('duration_hour') }}
                    </div>
                @endif
                <small>Stunden</small>
            </div>

            <div class="col-auto form-group">
                <input type="text" class="form-control {{ ($errors->has('duration_minute') ? 'is-invalid' : '') }}" id="duration_minute" name="duration_minute" value="{{ number_format($item->durationMinute, 0, ',', '') }}">
                @if ($errors->has('duration_minute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('duration_minute') }}
                    </div>
                @endif
                <small>Minuten</small>
            </div>

            <div class="col"></div>

        </div>

        <div class="form-group">
            <label for="revenue_account_number">Erlöskonto</label>
            <input type="text" class="form-control {{ ($errors->has('revenue_account_number') ? 'is-invalid' : '') }}" id="revenue_account_number" name="revenue_account_number" value="{{ $item->revenue_account_number }}">
            @if ($errors->has('revenue_account_number'))
                <div class="invalid-feedback">
                    {{ $errors->first('revenue_account_number') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="cost_center">Kostenstelle</label>
            <input type="text" class="form-control {{ ($errors->has('cost_center') ? 'is-invalid' : '') }}" id="cost_center" name="cost_center" value="{{ $item->cost_center }}">
            @if ($errors->has('cost_center'))
                <div class="invalid-feedback">
                    {{ $errors->first('cost_center') }}
                </div>
            @endif
        </div>

        <tag-select class="my-2" :selected="{{ json_encode($item->tags) }}" type="artikel" type_id="{{ $item->id }}"></tag-select>

        @include('customfieldvalue.edit', ['model' => $item])

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    @include('customfield.create', ['model' => $item])
@endsection