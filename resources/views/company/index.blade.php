@extends('layouts.layout')

@section('title', 'Firmen')

@section('content')

    <form action="{{ route('companies.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i></button>
    </form>

    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th width="90%">Bezeichnung</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td class="align-middle">{{ $company->name }}</td>
                    <td class="align-middle">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ url('/firmen', $company->id)}}" class="btn btn-secondary" title="Anzeigen"><i class="fas fa-fw fa-eye"></i></a>
                            <form action="{{ route('companies.switch.update', ['company' => $company->id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection