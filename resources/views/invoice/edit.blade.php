@extends('layouts.layout')

@section('title', $invoice->typeName . ' > ' . $invoice->name)

@section('buttons')
    <a href="{{ url('/belege/vorlage', $invoice->id) }}" class="btn btn-secondary btn-sm ml-1" title="Vorschau"><i class="fas fa-file-pdf"></i></a>
    <a href="{{ url('/belege/xrechnung', $invoice->id) }}" class="btn btn-secondary btn-sm ml-1" title="XRechnung">XRechnung</a>
    <a href="{{ url('/belege/pdf', $invoice->id) }}" class="btn btn-secondary btn-sm ml-1" title="Download"><i class="fas fa-download"></i></a>
    <button class="btn <?php echo $invoice->nextMainStatus ? 'btn-secondary' : 'btn-primary'; ?> btn-sm pointer ml-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
    @if ($invoice->nextMainStatus)
        <button class="btn btn-primary btn-sm pointer ml-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($invoice->nextMainStatus) }}">{{ ucfirst($invoice->nextMainStatus->action) }}</button>
    @endif
    @if ($invoice->isDunable())
        <form action="{{ url('/rechnungen/' . $invoice->id . '/mahnungen') }}" class="mb-0 ml-1" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Anmahnen</button>
        </form>
    @endif
    <a href="{{ $invoice->path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    <form action="{{ url('/rechnungen', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="number">Rechnungsnummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('number') is-invalid @enderror" id="number" name="number" value="{{ $invoice->number }}">
                                <small class="d-flex">
                                    <div class="col px-0">Nummernvorlage {{ $invoice->company->invoice_name_format }}</div>
                                    <a href="http://app.erp-olaf.test/einstellungen/nummernkreise">bearbeiten</a>
                                </small>
                                @error('number')
                                    <div class="invalid-feedback">
                                        $message
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <address-select selected-address="{{ $invoice->address }}" :selected-contact-id="{{ $invoice->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>

                        <order-select class="mb-1" :value="{{ json_encode($invoice->order) }}" :receipt-id="{{ $invoice->id }}"></order-select>

                        <tag-select class="my-2" :selected="{{ json_encode($invoice->tags) }}" index-path="{{ $invoice->tags_index_path }}" path="{{ $invoice->tags_path }}"></tag-select>

                        @if (count($invoice->partialinvoices) == 0)
                            @if (is_null($invoice->final_invoice_id))
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm" for="is_partial">Abschlagsrechnung</label>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <input class="form-check-input" id="is_partial" name="is_partial" type="checkbox" <?php echo ($invoice->is_partial ? 'checked="checked"' : '') ?> value="1">
                                        </div>
                                    </div>
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

                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <dates-edit :terms="{{ json_encode($terms) }}" :model="{{ $invoice }}"></dates-edit>
                        <boilerplate-input text-above-prop="{{ $invoice->text_above }}" text-below-prop="{{ $invoice->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>

                    </div>

                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-sm">Speichern</button>

    </form>
    <br />

    <receipt-item-table index-path="{{ \App\Receipts\Item::indexPath(['receipt_id' => $invoice->id]) }}" :model="{{ json_encode($invoice) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

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
