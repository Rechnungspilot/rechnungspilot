@extends('layouts.layout')

@section('title', 'Zugriffsrollen > ' . $role->name)

@section('buttons')
    <a href="{{ url('/zugriffsrollen') }}" class="btn btn-secondary btn-sm">Übersicht</a>
@endsection

@section('content')

    <form action="{{ url('/zugriffsrollen', $role->id) }}" class="mb-3" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="name">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="px-3">

                <table class="table table-fixed table-hover table-striped table-sm bg-white mt-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Create</th>
                            <th>Delete</th>
                            <th>Update</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $title => $group)
                            <tr>
                                <td>{{ $title }}</td>
                                @foreach($group as $permission)
                                    <td>
                                        <div class="form-group mb-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" name="permissions[]" {{ $role->hasPermissionTo($permission) ? 'checked="checked"' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            @if (false)

                @foreach($permissions as $title => $group)

                    <div class="col-md-4 py-3">
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

            @endif

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

@endsection