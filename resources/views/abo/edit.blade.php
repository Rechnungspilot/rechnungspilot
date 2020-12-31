@extends('layouts.layout')

@section('title', $abo->typeName . ' > ' . $abo->name ?: 'Noch nicht vergeben')

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
                    <form action="{{ url('/abos/aus', $abo->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            @if ($abo->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($abo->nextMainStatus) }}">{{ ucfirst($abo->nextMainStatus->action) }}</button>
            @endif
            <a href="{{ url('/abos/' . strtolower(class_basename($abo->settings->type))) }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/abos', $abo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="number">Belegnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $abo->number }}">
                    @if ($errors->has('number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('number') }}
                        </div>
                    @endif

                </div>

                <abo-contacts-select class="my-2" :model="{{ json_encode($abo) }}" :contacts="{{ json_encode($contacts) }}"></abo-contacts-select>

                <tag-select class="my-2" :selected="{{ json_encode($abo->tags) }}" type="abos" type_id="{{ $abo->id }}"></tag-select>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_patial" name="is_partial" value="1" <?php echo ($abo->is_partial ? 'checked="checked"' : '') ?>>
                    <label class="form-check-label" for="is_patial">Abschlagsrechnung</label>
                </div>

            </div>
            <div class="col">

                <abo-settings :model="{{ json_encode($abo->settings) }}" :interval-units="{{ json_encode($intervalUnits) }}" :send-mail-options="{{ json_encode($sendMailOptions) }}"></abo-settings>

            </div>

        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <receipt-item-table :model="{{ json_encode($abo) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

    @include('receipt.status.ul', ['statuses' => $abo->statuses])

    <comments uri="/abos" :item="{{ json_encode($abo) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/abos/' . $abo->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $abo->id }}" method="POST">
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
                axios.post('/belege/{{ $abo->id }}/status/create', {
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