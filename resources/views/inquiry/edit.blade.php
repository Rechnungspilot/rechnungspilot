@extends('layouts.layout')

@section('title', 'Anfrage > ' . $model->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/pdf', $model->id) }}" class="btn btn-secondary mr-1 pointer"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/anfragen/aus', $model->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Declined::class }}">Abgelehnt</button>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            @if ($model->nextMainStatus)
                <button class="btn btn-primary pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($model->nextMainStatus) }}">{{ ucfirst($model->nextMainStatus->action) }}</button>
            @elseif ($model->isQuoteable())
                <form action="{{ url('/angebote/aus', $model->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary pointer mr-1" type="submit">Angebot erstellen</button>
                </form>
            @endif
            <a href="{{ url('/anfragen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>
    <h3>Allgemein</h3>
    <form action="{{ url('/anfragen', $model->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="contact_id">Kontakt <a class="ml-1 pointer" href="{{ $model->contact->path }}"><i class="fas fa-external-link-alt"></i></a></label>
                    <select class="form-control {{ ($errors->has('contact_id') ? 'is-invalid' : '') }}" id="contact_id" name="contact_id">
                        @foreach($contacts as $option)
                            <option value="{{ $option->id }}" <?php echo ($model->contact_id == $option->id ? 'selected="selected"' : ''); ?>>{{ $option->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('contact_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('contact_id') }}
                        </div>
                    @endif
                </div>
                <tag-select class="my-2" :selected="{{ json_encode($model->tags) }}" type="briefe" type_id="{{ $model->id }}"></tag-select>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="date">Datum</label>
                    <date-input name="date" value="{{ $model->date }}" error="{{ ($errors->has('date') ? $errors->first('date') : '') }}"></date-input>
                </div>
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="name">Betreff</label>
                <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $model->name }}">
                @if ($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <editor-input name="text" value="{{ $model->text }}" error="{{ $errors->first('text') }}"></editor-input>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    <div class="card mb-5">
        <div class="card-header">Interaktionen</div>
        <div class="card-body">
            <interaction-table :model="{{ json_encode($model->contact) }}" :interactionable="{{ json_encode($model) }}"></interaction-table>
        </div>
    </div>

    @include('receipt.status.ul', ['statuses' => $model->statuses])

    <userfileable-table uri="/anfragen" :model="{{ json_encode($model) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/anfragen" :item="{{ json_encode($model) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/anfragen/' . $model->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $model->id }}" method="POST">
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
            $('#edit-item-modal').on('show.bs.modal', function (event) {
                $('#edit-item-modal div.modal-body').load('/belege/artikel/' + event.relatedTarget.id + '/edit');
            });

            $('#edit-item-modal').on('hidden.bs.modal', function (event) {
                $('#edit-item-modal div.modal-body').html('');
            })

            $('table#receiptitems tbody td:not(.buttons)').click( function () {
                $('#edit-item-modal').modal('show', { id: $(this).closest('tr').attr('data-receiptitem-id') });
            });

            $('#statusModal').on('show.bs.modal', function (e) {
                axios.post('/belege/{{ $model->id }}/status/create', {
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