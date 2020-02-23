@extends('layouts.layout')

@section('title', 'Zugriffsrollen > ' . $role->name)

@section('content')

    <div class="row text-right">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url('/zugriffsrollen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ url('/zugriffsrollen', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $role->name }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="container-fluid">
            <div class="row">
                @foreach($permissions as $title => $group)

                    <div class="col-md-4 p-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $title }}</h5>
                                <p class="card-text">
                                    @foreach($group as $permission)
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" name="permissions[]" {{ $role->hasPermissionTo($permission) ? 'checked="checked"' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>

@endsection