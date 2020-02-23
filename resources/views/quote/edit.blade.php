@extends('layouts.layout')

@section('title', 'Angebot > ' . $quote->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/pdf', $quote->id) }}" class="btn btn-secondary mr-1 pointer"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/angebote/aus', $quote->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <form action="{{ url('/auftraege/aus', $quote->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Auftrag erstellen</button>
                    </form>
                    <form action="{{ url('/rechnungen/aus', $quote->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Rechnung erstellen</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Declined::class }}">Abgelehnt</button>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <button class="btn <?php echo ($quote->nextMainStatus || $quote->isInvoiceable()) ? 'btn-secondary' : 'btn-primary'; ?> pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
            @if ($quote->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($quote->nextMainStatus) }}">{{ ucfirst($quote->nextMainStatus->action) }}</button>
            @elseif ($quote->isOrderable())
                <form action="{{ url('/auftraege/aus', $quote->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary pointer mr-1" type="submit">Auftrag erstellen</button>
                </form>
            @endif
            <a href="{{ url('/angebote') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/angebote', $quote->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col">
                <div class="form-group">
                    <label for="number">Angebotsnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $quote->number }}">
                    @if ($errors->has('number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('number') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="subject">Betreff</label>
                    <input type="text" class="form-control {{ ($errors->has('subject') ? 'is-invalid' : '') }}" id="subject" name="subject" value="{{ $quote->subject }}">
                    @if ($errors->has('subject'))
                        <div class="invalid-feedback">
                            {{ $errors->first('subject') }}
                        </div>
                    @endif
                </div>

                <address-select selected-address="{{ $quote->address }}" :selected-contact-id="{{ $quote->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>
                <br />
                <tag-select class="my-2" :selected="{{ json_encode($quote->tags) }}" type="angebote" type_id="{{ $quote->id }}"></tag-select>
            </div>
            <div class="col">

                <dates-edit :terms="{{ json_encode($terms) }}" :model="{{ $quote }}"></dates-edit>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col">

                <div class="card">

                    <div class="card-body">
                        <boilerplate-input text-above-prop="{{ $quote->text_above }}" text-below-prop="{{ $quote->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>
                    </div>

                </div>

            </div>

        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <receipt-item-table :model="{{ json_encode($quote) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

    <h5>Termine</h5>
    <appointment-table :model="{{ json_encode($quote) }}" :initial-contact="{{ json_encode($quote->contact) }}" :users="{{ json_encode($users) }}"></appointment-table>

    @include('receipt.status.ul', ['statuses' => $quote->statuses])

    <userfileable-table uri="/angebote" :model="{{ json_encode($quote) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/angebote" :item="{{ json_encode($quote) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/angebote/' . $quote->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $quote->id }}" method="POST">
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
                axios.post('/belege/{{ $quote->id }}/status/create', {
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