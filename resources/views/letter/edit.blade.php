@extends('layouts.layout')

@section('title', 'Brief > ' . $letter->name)

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="{{ url('/belege/pdf', $letter->id) }}" class="btn btn-secondary mr-1 pointer"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Anlegen</h6>
                    <form action="{{ url('/briefe/aus', $letter->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item pointer">Duplizieren</button>
                    </form>
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <button class="btn  <?php echo $letter->nextMainStatus ? 'btn-secondary' : 'btn-primary'; ?> pointer mr-1" data-toggle="modal" data-target="#statusModal" data-status="{{ App\Receipts\Statuses\Send::class }}">Versenden</button>
            <a href="{{ url('/briefe') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <h3>Allgemein</h3>
    <form action="{{ url('/briefe', $letter->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <address-select selected-address="{{ $letter->address }}" :selected-contact-id="{{ $letter->contact_id }}" :contacts="{{ json_encode($contacts) }}"></address-select>
                <br />
                <tag-select class="my-2" :selected="{{ json_encode($letter->tags) }}" type="briefe" type_id="{{ $letter->id }}"></tag-select>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="date">Datum</label>
                    <date-input name="date" value="{{ $letter->date }}" error="{{ ($errors->has('date') ? $errors->first('date') : '') }}"></date-input>
                </div>
                <boilerplate-input text-above-prop="{{ $letter->text_above }}" text-below-prop="{{ $letter->text_below }}" :boilerplates="{{ json_encode($boilerplates) }}" :placeholders="{{ json_encode($placeholders) }}"></boilerplate-input>
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="subject">Betreff</label>
                <input type="text" class="form-control {{ ($errors->has('subject') ? 'is-invalid' : '') }}" id="subject" name="subject" value="{{ $letter->subject }}">
                @if ($errors->has('subject'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subject') }}
                    </div>
                @endif
            </div>

            <editor-input name="text" value="{{ $letter->text }}" error="{{ $errors->first('text') }}"></editor-input>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>

    </form>
    <br />

    @include('receipt.status.ul', ['statuses' => $letter->statuses])

    <userfileable-table uri="/briefe" :model="{{ json_encode($letter) }}" token="{{ csrf_token() }}"></userfileable-table>

    <comments uri="/briefe" :item="{{ json_encode($letter) }}"></comments>

    @include('receipt.confirm-destroy', ['route' => '/briefe/' . $letter->id])

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <form action="/belege/status/{{ $letter->id }}" method="POST">
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
                axios.post('/belege/{{ $letter->id }}/status/create', {
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