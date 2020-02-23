@extends('layouts.layout')

@section('title', $invoice->typeName . ' > ' . $invoice->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/vorlage', $invoice->id) }}" class="btn btn-secondary mr-1" title="Vorschau"><i class="fas fa-file-pdf"></i></a>
            <a href="{{ url('/belege/pdf', $invoice->id) }}" class="btn btn-secondary mr-1" title="Download"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/rechnungen/aus', $invoice->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <form action="{{ url('/rechnungen/aus', $invoice->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="credit" value="1">
                        <button type="submit" class="dropdown-item pointer">Gutschrift erstellen</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <button class="btn <?php echo $invoice->nextMainStatus ? 'btn-secondary' : 'btn-primary'; ?> pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
            @if ($invoice->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($invoice->nextMainStatus) }}">{{ ucfirst($invoice->nextMainStatus->action) }}</button>
            @endif
            @if ($invoice->isDunable())
                <form action="{{ url('/rechnungen/' . $invoice->id . '/mahnungen') }}" class="mb-0 mr-1" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Anmahnen</button>
                </form>
            @endif
            <a href="{{ url('/rechnungen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/rechnungen', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="number">Rechnungsnummer</label>
                    <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $invoice->number }}">
                    <small class="d-flex">
                        <div class="col px-0">Nummernvorlage {{ $invoice->company->invoice_name_format }}</div>
                        <a href="http://app.erp-olaf.test/einstellungen/nummernkreise">bearbeiten</a>
                    </small>
                    @if ($errors->has('number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('number') }}
                        </div>
                    @endif
                </div>

                <address-select selected-address="{{ $invoice->address }}" :selected-contact-id="{{ $invoice->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>

                <order-select class="mb-1" :value="{{ json_encode($invoice->order) }}" receipt-id="{{ $invoice->id }}"></order-select>

                <tag-select class="my-2" :selected="{{ json_encode($invoice->tags) }}" type="rechnungen" type_id="{{ $invoice->id }}"></tag-select>

                @if (count($invoice->partialinvoices) == 0)
                    @if (is_null($invoice->final_invoice_id))
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_partial" name="is_partial" <?php echo ($invoice->is_partial ? 'checked="checked"' : '') ?> value="1">
                            <label class="form-check-label" for="is_partial">Abschlagsrechnung</label>
                        </div>
                    @elseif ($invoice->final_invoice_id)
                        <div class="card">
                            <div class="card-body">
                                Abschlagsrechnung für <a href="{{ $invoice->finalinvoice->path }}">{{ $invoice->finalinvoice->name }}</a>
                            </div>
                        </div>
                    @endif
                @endif
                @if(count($invoice->possiblePartials) && $invoice->is_partial == false)
                    <div class="mt-3">Abschlagsrechnungen</div>
                    <invoice-partials :id="{{ $invoice->id }}" :possibles="{{ json_encode($invoice->possiblePartials) }}"></invoice-partials>
                @endif
            </div>
            <div class="col">

                <dates-edit :terms="{{ json_encode($terms) }}" :model="{{ $invoice }}"></dates-edit>
                <boilerplate-input text-above-prop="{{ $invoice->text_above }}" text-below-prop="{{ $invoice->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <receipt-item-table :model="{{ json_encode($invoice) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

    @include('receipt.status.ul', ['statuses' => $invoice->statuses])

    <userfileable-table uri="/rechnungen" :model="{{ json_encode($invoice) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/rechnungen" :item="{{ json_encode($invoice) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/rechnungen/' . $invoice->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $invoice->id }}" method="POST">
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
                axios.post('/belege/{{ $invoice->id }}/status/create', {
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