@extends('layouts.layout')

@section('title', 'Lieferschein > ' . $delivery->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/pdf', $delivery->id) }}" class="btn btn-secondary mr-1 pointer"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/lieferscheine/aus', $delivery->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <form action="{{ url('/auftraege/aus', $delivery->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Auftrag erstellen</button>
                    </form>
                    <form action="{{ url('/rechnungen/aus', $delivery->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Rechnung erstellen</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Declined::class }}">Abgelehnt</button>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <button class="btn <?php echo ($delivery->nextMainStatus || $delivery->isInvoiceable()) ? 'btn-secondary' : 'btn-primary'; ?> pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
            @if ($delivery->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($delivery->nextMainStatus) }}">{{ ucfirst($delivery->nextMainStatus->action) }}</button>
            @elseif ($delivery->isInvoiceable())
                <form action="{{ url('/rechnungen/aus', $delivery->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary pointer mr-1" type="submit">Rechnung erstellen</button>
                </form>
            @endif
            <a href="{{ url('/lieferscheine') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/lieferscheine', $delivery->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="number">Lieferscheinnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $delivery->number }}">
                    @if ($errors->has('number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('number') }}
                        </div>
                    @endif
                </div>

                <address-select selected-address="{{ $delivery->address }}" :selected-contact-id="{{ $delivery->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>
                <br />
                <tag-select class="my-2" :selected="{{ json_encode($delivery->tags) }}" type="lieferscheine" type_id="{{ $delivery->id }}"></tag-select>
            </div>
            <div class="col">

                <div class="form-group">
                    <label for="date">Lieferdatum</label>
                    <date-input name="date" value="{{ $delivery->date }}" error="{{ ($errors->has('date') ? $errors->first('date') : '') }}"></date-input>
                </div>
                <boilerplate-input text-above-prop="{{ $delivery->text_above }}" text-below-prop="{{ $delivery->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <receipt-item-table :model="{{ json_encode($delivery) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

    @include('receipt.status.ul', ['statuses' => $delivery->statuses])

    <userfileable-table uri="/lieferscheine" :model="{{ json_encode($delivery) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/lieferscheine" :item="{{ json_encode($delivery) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/lieferscheine/' . $delivery->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $delivery->id }}" method="POST">
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
                axios.post('/belege/{{ $delivery->id }}/status/create', {
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