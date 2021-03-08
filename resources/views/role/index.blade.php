@extends('layouts.layout')

@section('title', 'Zugriffsrollen')

@section('buttons')
    <a href="{{ \App\User::indexPath() }}" class="btn btn-secondary btn-sm">Übersicht</a>
@endsection

@section('content')

    <div class="mb-3">
        <form action="/zugriffsrollen" method="POST">
            @csrf

            <button type="submit" class="btn btn-primary btn-sm" role="button"><i class="fas fa-plus-square"></i></button>
        </form>
    </div>

    <table class="table table-fixed table-hover table-striped table-sm bg-white">

        <thead>
            <tr>
                <th width="90%">Name</th>
            </tr>

        </thead>

        <tbody>

            @foreach($roles as $role)

                <tr>
                    <td class="align-middle"><a href="zugriffsrollen/{{ $role->getRouteKey() }}">{{ $role->name }}</a></td>
                </tr>

            @endforeach

        </tbody>

    </table>

@endsection