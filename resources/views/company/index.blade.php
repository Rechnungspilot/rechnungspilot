@extends('layouts.layout')

@section('title', 'Firmen')

@section('content')

    <form action="{{ route('companies.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i></button>
    </form>

    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Aktuelle Firma</b></div>
                        <div class="col-value">{{ $current_company->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover table-sm mt-3">
        <thead>
            <tr>
                <th width="100">ID</th>
                <th width="100%">Bezeichnung</th>
                <th width="100"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td class="align-middle">{{ $company->id }}</td>
                    <td class="align-middle">{{ $company->name }}</td>
                    <td class="align-middle text-right">
                        <form action="{{ route('companies.switch.update', ['company' => $company->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="btn-group btn-group-sm">
                                <a href="{{ url('/firmen', $company->id)}}" class="btn btn-secondary" title="Anzeigen"><i class="fas fa-fw fa-eye"></i></a>
                                <button type="submit" class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i></button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection