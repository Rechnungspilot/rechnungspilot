@extends('layouts.layout')

@section('title', 'Lieferanten')

@section('content')

    <form action="{{ url('/lieferanten') }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-primary" role="button">Anlegen</button>
    </form>
    <br />
    <table class="table table-hover table-striped bg-white">

        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="80%">Name</th>
                <th class="text-right" width="10%">Aktion</th>
            </tr>

        </thead>

        <tbody>

            @foreach($suppliers as $supplier)

                <tr>
                    <td class="align-middle"><a href="{{ url('/lieferanten', $supplier->id) }}">{{ $supplier->number }}</a></td>
                    <td class="align-middle"><a href="{{ url('/lieferanten', $supplier->id) }}">{{ $supplier->name }}</a></td>
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