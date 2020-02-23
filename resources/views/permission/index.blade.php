@extends('layouts.layout')

@section('title', 'Berechtigungen')

@section('content')

    <form action="{{ url('/berechtigungen') }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-primary" role="button">Anlegen</button>
    </form>

    <br /><br />
    <table class="table table-hover table-striped bg-white">

        <thead>
            <tr>
                <th width="90%">Name</th>
                <th class="text-right" width="10%">Aktion</th>
            </tr>

        </thead>

        <tbody>

            @foreach($permissions as $permission)

                <tr>
                    <td class="align-middle"><a href="{{ url('/berechtigungen, $permission->getRouteKey()) }}">{{ $permission->name }}</a></td>
                    <td class="text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-secondary">Left</button>
                            <button type="button" class="btn btn-secondary">Middle</button>
                            <button type="button" class="btn btn-secondary">Right</button>
                        </div>
                    </td>
                </tr>

            @endforeach

        </tbody>

    </table>

@endsection