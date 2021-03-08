@extends('layouts.layout')

@section('title', $user->label() . ' > ' . $user->name)

@section('buttons')
    @if(!$user->password)
        <form action="{{ $user->path . '/einladen' }}" method="POST">
            @csrf

            <input id="email" type="hidden" class="form-control" name="email" value="{{ $user->email }}">
            <button type="submit" class="btn btn-success">
                Einladen
            </button>
        </form>
    @endif
    <a href="{{ $user->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    <a class="btn btn-secondary btn-sm ml-1" href="{{ $user->index_path }}" title="Übersicht">Übersicht</a>
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-header">{{ $user->name }}</div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    @if ($user->number)
                        <div class="row">
                            <div class="col-label"><b>Personalnummer</b></div>
                            <div class="col-value">{{ $user->number }}</div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-label"><b>Name</b></div>
                        <div class="col-value">{{ $user->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Kategorien</b></div>
                        <div class="col-value">{{ $user->tagsString ?: 'Keine Kategorien vergeben' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Zugriffsrollen</b></div>
                        <div class="col-value">{{ $user->roles_string }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Farbe</b></div>
                        <div class="col-value p-1"><div class="p-2" style="background-color: {{ $user->hex_color_code }}"></div></div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row">
                        <div class="col-label"><b>Telefon</b></div>
                        <div class="col-value">{{ $user->phonenumber }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Mobil</b></div>
                        <div class="col-value">{{ $user->mobilenumber }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>E-Mail</b></div>
                        <div class="col-value">{{ $user->email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Anschrift</b></div>
                        <div class="col-value">
                            {{ $user->name }}<br />
                            {{ $user->address }}<br />
                            {{ $user->postcode }} {{ $user->city }}<br />
                            {{ $user->country }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-3">

                <div class="card-body">

                    <comments uri="/team" :item="{{ json_encode($user) }}"></comments>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card mb-3">

                <div class="card-body">

                    <userfileable-table uri="/team" :model="{{ json_encode($user) }}" token="{{ csrf_token() }}"></userfileable-table>

                </div>

            </div>

        </div>

    </div>

@endsection