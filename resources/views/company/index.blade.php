@extends('layouts.layout')

@section('title', 'Firmen')

@section('content')

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="90%">Bezeichnung</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>
                        <div class="btn-group  btn-group-sm">
                            <a href="{{ url('/firmen', $company->id)}}" class="btn btn-secondary" title="Anzeigen"><i class="fas fa-fw fa-eye"></i></a>
                            <button class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection