@extends('layouts.layout')

@section('title', $income->typeName . ' > ' . $income->name ?: 'Noch nicht vergeben')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/einnahmen/aus', $income->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            @if ($income->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($income->nextMainStatus) }}">{{ ucfirst($income->nextMainStatus->action) }}</button>
            @endif
            <a href="{{ url('/einnahmen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/einnahmen', $income->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="name">Belegnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $income->name }}">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="contact_id">Konatakt</label>
                    <select class="form-control {{ ($errors->has('contact_id') ? 'is-invalid' : '') }}" id="contact_id" name="contact_id">
                        @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}" {{ ($contact->id == $income->contact_id ? 'selected="selected"' : '') }}>{{ $contact->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('contact_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('contact_id') }}
                        </div>
                    @endif
                </div>

                <tag-select class="my-2" :selected="{{ json_encode($income->tags) }}" type="einnahmen" type_id="{{ $income->id }}"></tag-select>

            </div>
            <div class="col">

                <div class="form-group">
                    <label for="date">Datum</label>
                    <date-input name="date" value="{{ $income->date }}" error="{{ ($errors->has('date') ? $errors->first('date') : '') }}"></date-input>
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    {{-- <receipt-item-table :model="{{ json_encode($income) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table> --}}

    <div class="row mb-3">
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <userfileable-receipt-single :model="{{ json_encode($income) }}"></userfileable-receipt-single>
        </div>
        <div class="col">
            <receipt-item-table :model="{{ json_encode($income) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>
        </div>
    </div>

    @include('receipt.status.ul', ['statuses' => $income->statuses])

    <comments uri="/einnahmen" :item="{{ json_encode($income) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/einnahmen/' . $income->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $income->id }}" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function () {
            $('#statusModal').on('show.bs.modal', function (e) {
                axios.post('/belege/{{ $income->id }}/status/create', {
                    type: $(e.relatedTarget).attr('data-status')
                })
                    .then(function (response) {
                        $('.modal-title', '#statusModal').html(response.data.title);
                        $('.modal-body', '#statusModal').html(response.data.body);
                        $('.modal-footer .btn-primary', '#statusModal').html(response.data.action);
                });
            });
        });
    </script>

@endsection