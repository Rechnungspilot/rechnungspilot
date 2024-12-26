@extends('layouts.layout')

@section('title', 'API Tokens')

@section('content')

    <form action="{{ route('tokens.store') }}" class="form-inline" method="POST">
        @csrf

        <div class="form-group">
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" placeholder="Name" required>
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <button type="submit" class="ml-1 btn btn-primary"><i class="fas fa-plus-square"></i></button>
    </form>

    @if(session('token'))
        <div class="alert alert-success mt-3">
            {{ session('token')->plainTextToken }}
        </div>
    @endif

    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th width="20%">Bezeichnung</th>
                <th width="70%">Token</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($tokens as $token)
                <tr>
                    <td class="align-middle">{{ $token->name }}</td>
                    <td class="align-middle">{{ $token->token }}</td>
                    <td class="align-middle">
                        <div class="btn-group btn-group-sm">
                            <form action="{{ route('tokens.destroy', ['token' => $token->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-secondary"><i class="fas fa-fw fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
