@extends('layouts.layout')

@section('title', 'Interaktionen > ' . $model->name)

@section('content')

    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto d-flex">
            <a href="{{ url($model->path . '/edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
            <a href="{{ $model->contact->path }}" class="btn btn-secondary ml-1">Kontakt</a>
            @if($model->interactionable)
                <a href="{{ $model->interactionable->path }}" class="btn btn-secondary ml-1">{{ $model->interactionable->name }}</a>
            @endif
            @if ($model->isDeletable())
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">{{ $model->name }}</div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4"><b>Typ</b></div>
                        <div class="col-md-8">{{ $model->type->name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><b>Datum</b></div>
                        <div class="col-md-8">{{ $model->at->format('d.m.Y H:i') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><b>Kontakt</b></div>
                        <div class="col-md-8">{{ $model->contact->name }}</div>
                    </div>

                    @if($model->person_id)
                        <div class="row">
                            <div class="col-md-4"><b>Ansprechpartner</b></div>
                            <div class="col-md-8">{{ $model->person->name }}</div>
                        </div>
                    @endif

                    @if($model->user_id)
                        <div class="row">
                            <div class="col-md-4"><b>Team</b></div>
                            <div class="col-md-8">{{ $model->user->name }}</div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

        <div class="col-md-6">
            <div class="card">

                <div class="card-header">Verlauf</div>
                <div class="card-body">
                    <interaction-table :model="{{ json_encode($model->contact) }}" :interaction="{{ json_encode($model) }}"></interaction-table>
                </div>

            </div>

        </div>

    </div>

    @if($model->text)
        <div class="row mb-5">

            <div class="col">

                <div class="card">

                    <div class="card-body">
                        {{ $model->text }}
                    </div>

                </div>

            </div>

        </div>
    @endif

@endsection