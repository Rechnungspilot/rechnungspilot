@extends('layouts.layout')

@section('title', $item->label() . ' > ' . $item->name)

@section('buttons')
    <a href="{{ url($item->path) }}" class="btn btn-secondary btn-sm">Artikel</a>
@endsection

@section('content')

    <form action="{{ $item->path }}" class="mb-3" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="type">Typ</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm @error('type') is-invalid @enderror" id="type" name="type">
                                    @foreach ($types as $key => $name)
                                        <option value="{{ $key }}" {{ $item->type == $key ? 'selected="selected"' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="number">Artikelnummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('number') is-invalid @enderror" id="number" name="number" value="{{ $item->number }}">
                                @error('number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="name">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ $item->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="description">Beschreibung</label>
                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ $item->description }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="unit_price">Preis/Einheit</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('unit_price') is-invalid @enderror" id="unit_price" name="unit_price" value="{{ number_format($item->unit_price, $item->decimals, ',', '') }}">
                                @error('unit_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="decimals">Nachkommastellen</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm @error('decimals') is-invalid @enderror" id="decimals" name="decimals">
                                    @foreach($decimals as $decimal)
                                        <option value="{{ $decimal }}" {{ $item->decimals == $decimal ? 'selected="selected"' : '' }}>{{ $decimal}}</option>
                                    @endforeach
                                </select>
                                @error('decimals')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="unit_id">Einheit</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id}}" {{ $item->unit->id == $unit->id ? 'selected="selected"' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="tax">USt.</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm @error('tax') is-invalid @enderror" id="tax" name="tax">
                                    <option value="0.19" {{ $item->tax == 0.19 ? 'selected="selected"' : '' }}>19%</option>
                                    <option value="0.16" {{ $item->tax == 0.16 ? 'selected="selected"' : '' }}>16%</option>
                                    <option value="0.107" {{ $item->tax == 0.107 ? 'selected="selected"' : '' }}>10,7%</option>
                                    <option value="0.07" {{ $item->tax == 0.07 ? 'selected="selected"' : '' }}>7%</option>
                                    <option value="0" {{ $item->tax == 0 ? 'selected="selected"' : '' }}>0%</option>
                                </select>
                                @error('tax')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="unit_cost">Einkaufspreis</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('unit_cost') is-invalid @enderror" id="unit_cost" name="unit_cost" value="{{ number_format($item->unit_cost, $item->decimals, ',', '') }}">
                                @error('unit_cost')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row align-items-center mb-0">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="duration_hour">Dauer</label>
                            <div class="col-sm-8 row">
                                <div class="col">
                                    <input type="text" class="form-control form-control-sm @error('duration_hour') is-invalid @enderror" id="duration_hour" name="duration_hour" value="{{ number_format($item->durationHour, 0, ',', '') }}">
                                    @error('duration_hour')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small>Stunden</small>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-sm @error('duration_minute') is-invalid @enderror" id="duration_minute" name="duration_minute" value="{{ number_format($item->durationMinute, 0, ',', '') }}">
                                    @error('duration_minute')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="col-auto form-group">
                                        <small>Minuten</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <tag-select class="my-2" :selected="{{ json_encode($item->tags) }}" index-path="{{ $item::indexPathTags() }}" path="{{ $item->tags_path }}"></tag-select>

                    </div>

                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Buchhaltung</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="expense_account_number">Buchungskonto Ausgabe</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('expense_account_number') is-invalid @enderror" id="expense_account_number" name="expense_account_number" value="{{ $item->expense_account_number }}">
                                @error('expense_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="revenue_account_number">Erlöskonto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('revenue_account_number') is-invalid @enderror" id="revenue_account_number" name="revenue_account_number" value="{{ $item->revenue_account_number }}">
                                @error('revenue_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="cost_center">Kostenstelle</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('cost_center') is-invalid @enderror" id="cost_center" name="cost_center" value="{{ $item->cost_center }}">
                                @error('cost_center')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>

                @include('customfieldvalue.edit', ['model' => $item])

            </div>

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

    @include('customfield.create', ['model' => $item])
@endsection