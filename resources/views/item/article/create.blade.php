@extends('layouts.layout')

@section('title', $model->name . ' > Artikel anlegen ' . now()->format('d.m.Y'))

@section('buttons')
    <a href="{{ $model->path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-header">Artikel</div>
        <div class="card-body">
            <items-articles-table :model="{{ json_encode($model) }}" index-path="{{ \App\Models\Items\Article::indexPath(['item_id' => $model->id]) }}" created-at-date="{{ now()->format('Y-m-d') }}"></items-articles-table>
        </div>
    </div>

@endsection