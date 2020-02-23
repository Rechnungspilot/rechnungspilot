@extends('layouts.layout')

@section('title', 'Eintellungen > Mahnstufen')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="form-group mb-0">
                <form action="{{ url('/einstellungen/mahnstufen') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-primary" title="Anlegen"><i class="fas fa-plus-square"></i></button>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td class="align-middle" width="10%">Stufe</td>
                <td class="align-middle" width="30%">Bezeichnung</td>
                <td class="align-middle" width="40%">Artikel</td>
                <td class="align-middle text-right" width="10%">Preis</td>
                <td class="align-middle text-right" width="10%">Aktion</td>
            </tr>
        </thead>
        <tbody>
            @foreach($levels as $level)
                <tr>
                    <td class="align-middle">{{ $level->level }}</td>
                    <td class="align-middle">{{ $level->name }}</td>
                    <td class="align-middle" width="40%">{{ $level->item ? $level->item->name : '-' }}</td>
                    <td class="align-middle text-right" width="10%">{{ number_format($level->amount / 100, 2, ',', '.') }} €</td>
                    <td class="align-middle text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ url($level->path . '/edit') }}" type="button" class="btn btn-secondary" title="Bearbeiten"><i class="fas fa-fw fa-edit"></i></a>
                            <button type="button" class="btn btn-secondary" title="Löschen"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection