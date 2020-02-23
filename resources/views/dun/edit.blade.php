@extends('layouts.layout')

@section('title', $dun->typeName . ' > ' . $dun->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/pdf', $dun->id) }}" class="btn btn-secondary mr-1"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <button class="btn <?php echo $dun->nextMainStatus ? 'btn-secondary' : 'btn-primary'; ?> pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
            <a href="{{ url('/mahnungen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/mahnungen', $dun->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="number">Mahnungsnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $dun->number }}">
                    @if ($errors->has('number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('number') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="subject">Betreff</label>
                    <input type="text" class="form-control {{ ($errors->has('subject') ? 'is-invalid' : '') }}" id="subject" name="subject" value="{{ $dun->subject }}">
                    @if ($errors->has('subject'))
                        <div class="invalid-feedback">
                            {{ $errors->first('subject') }}
                        </div>
                    @endif
                </div>

                <address-select selected-address="{{ $dun->address }}" :selected-contact-id="{{ $dun->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>
                <tag-select class="my-2" :selected="{{ json_encode($dun->tags) }}" type="mahnungen" type_id="{{ $dun->id }}"></tag-select>

            </div>
            <div class="col">

                <div class="form-group">
                    <label for="date">Datum</label>
                    <date-input name="date" value="{{ $dun->date }}" error="{{ ($errors->has('date') ? $errors->first('date') : '') }}"></date-input>
                </div>
                <boilerplate-input text-above-prop="{{ $dun->text_above }}" text-below-prop="{{ $dun->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <receipt-item-table :model="{{ json_encode($dun) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

    @include('receipt.status.ul', ['statuses' => $dun->statuses])

    <userfileable-table uri="/mahnungen" :model="{{ json_encode($dun) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/mahnungen" :item="{{ json_encode($dun) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/mahnungen/' . $dun->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $dun->id }}" method="POST">
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
                axios.post('/belege/{{ $dun->id }}/status/create', {
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