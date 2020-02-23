@extends('layouts.layout')

@section('title', 'Interaktionen > ' . $model->name)

@section('content')

    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto d-flex">
            <a href="{{ $model->path }}" class="btn btn-secondary">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <form action="{{ $model->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row mb-3">

            <div class="col-md-6">

                <div class="card">

                    <div class="card-body">

                        <div class="form-group">
                            <label for="name">Betreff</label>
                            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ old('name') ?? $model->name }}">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="interaction_type_id">Typ</label>
                            <select class="form-control {{ ($errors->has('interaction_type_id') ? 'is-invalid' : '') }}" id="interaction_type_id" name="interaction_type_id">
                                @foreach($types as $value)
                                    <option value="{{ $value->id }}" <?php echo ($model->interaction_type_id == $value->id ? 'selected="selected"' : ''); ?>>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('interaction_type_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('interaction_type_id') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="card">

                    <div class="card-body">

                        <div class="form-group">
                            <label for="at">Datum</label>
                            <datetime-input name="at" value="{{ $model->at }}" error="{{ ($errors->has('at') ? $errors->first('at') : '') }}"></datetime-input>
                        </div>

                        <div class="form-group">
                            <label for="person_id">Anprechpartner</label>
                            <select class="form-control {{ ($errors->has('person_id') ? 'is-invalid' : '') }}" id="person_id" name="person_id">
                                <option value="0">Kein Ansprechpartner</option>
                                @foreach($people as $value)
                                    <option value="{{ $value->id }}" <?php echo ($model->person_id == $value->id ? 'selected="selected"' : ''); ?>>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('person_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('person_id') }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="form-row mb-3">

            <div class="col">

                <div class="card">

                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" name="text" rows="15">{{ $model->text }}</textarea>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <ckeditor :editor="classic"></ckeditor>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>

@endsection