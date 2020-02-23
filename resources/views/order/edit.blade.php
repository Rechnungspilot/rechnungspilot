@extends('layouts.layout')

@section('title', $order->typeName . ' > ' . $order->name)

@section('content')

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">

                <ul class="nav nav-pills mt-0" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Allgemein</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Geleistet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Kalkulation</a>
                    </li>
                </ul>

            </div>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/auftraege/aus', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <form action="{{ url('/rechnungen/aus', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Rechnung erstellen</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            @if ($order->nextMainStatus)
                <button class="btn {{ $order->isInvoiceable() ? 'btn-secondary' : 'btn-primary' }} pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($order->nextMainStatus) }}">{{ ucfirst($order->nextMainStatus->action) }}</button>
            @elseif ($order->isInvoiceable())
                <form action="{{ url('/rechnungen/aus', $order->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary pointer mr-1" type="submit">Rechnung erstellen</button>
                </form>
            @endif
            <a href="{{ url('/auftraege') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ url('/auftraege', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <h5>Allgemein</h5>
                        <div class="form-group">
                            <label for="number">Auftragsnummer</label>
                            <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ $order->number }}">
                            @if ($errors->has('number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('number') }}
                                </div>
                            @endif
                        </div>

                        <address-select selected-address="{{ $order->address }}" :selected-contact-id="{{ $order->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>
                        <tag-select class="my-2" :selected="{{ json_encode($order->tags) }}" type="auftraege" type_id="{{ $order->id }}"></tag-select>

                    </div>
                    <div class="col">

                        @include('customfieldvalue.edit', ['model' => $order])

                        <h5>Termine</h5>
                        <appointment-table :model="{{ json_encode($order) }}" :initial-contact="{{ json_encode($order->contact) }}" :users="{{ json_encode($users) }}"></appointment-table>

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Speichern</button>

            </form>
            <br />

            <receipt-item-table :model="{{ json_encode($order) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

            @if(count($order->invoices) > 0)
                <h5>Rechnungen</h5>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <td>Nummer</td>
                            <td>Netto</td>
                            <td>Brutto</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->invoices as $receipt)
                            <tr>
                                <td><a href="{{ $receipt->path }}" target="_blank">{{ $receipt->name }}</a></td>
                                <td>{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                                <td>{{ number_format($receipt->gross / 100, 2, ',', '.') }} €</td>
                                <td>{{ $receipt->status->name }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(count($order->expenses) > 0)
                <h5>Ausgaben</h5>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <td>Nummer</td>
                            <td>Netto</td>
                            <td>Brutto</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->expenses as $receipt)
                            <tr>
                                <td><a href="{{ $receipt->path }}" target="_blank">{{ $receipt->name }}</a></td>
                                <td>{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                                <td>{{ number_format($receipt->gross / 100, 2, ',', '.') }} €</td>
                                <td>{{ $receipt->status->name }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @include('receipt.status.ul', ['statuses' => $order->statuses])

            <userfileable-table uri="/auftraege" :model="{{ json_encode($order) }}" token="{{ csrf_token() }}"></userfileable-table>

            <comments uri="/auftraege" :item="{{ json_encode($order) }}"></comments>

            @include('receipt.confirm-destroy', ['route' => '/auftraege/' . $order->id])

            <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
                <div class="modal-dialog" role="document">
                    <form action="/belege/status/{{ $order->id }}" method="POST">
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
        </div>
        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
        <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">...</div>
    </div>

    @include('customfield.create', ['model' => $order])

    <script type="text/javascript">
        $(document).ready( function () {
            $('#statusModal').on('show.bs.modal', function (e) {
                axios.post('/belege/{{ $order->id }}/status/create', {
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