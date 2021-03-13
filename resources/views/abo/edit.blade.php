@extends('layouts.layout')

@section('title', $abo->typeName . ' > ' . $abo->name ?: 'Noch nicht vergeben')

@section('buttons')
    <a href="{{ $abo->path }}" class="btn btn-secondary btn-sm">Übersicht</a>
@endsection

@section('content')

    <form action="{{ $abo->path }}" class="mb-3" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="number">Belegnummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('number') is-invalid @enderror" id="number" name="number" value="{{ $abo->number }}">
                                @error('number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="is_partial">Abschlagsrechnung</label>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_partial" name="is_partial" value="1" <?php echo ($abo->is_partial ? 'checked="checked"' : '') ?>>
                                </div>
                            </div>
                        </div>

                        <tag-select class="my-2" :selected="{{ json_encode($abo->tags) }}" index-path="{{ $abo->indexPathTags() }}" path="{{ $abo->tags_path }}"></tag-select>

                    </div>

                </div>


                <abo-contacts-select class="my-2" :model="{{ json_encode($abo) }}" :contacts="{{ json_encode($contacts) }}"></abo-contacts-select>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Einstellungen</div>
                    <div class="card-body">

                        <abo-settings :model="{{ json_encode($abo->settings) }}" :interval-units="{{ json_encode($intervalUnits) }}" :send-mail-options="{{ json_encode($sendMailOptions) }}"></abo-settings>

                    </div>

                </div>

            </div>

        </div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>
    <br />

    <receipt-item-table index-path="{{ \App\Receipts\Item::indexPath(['receipt_id' => $abo->id]) }}" :model="{{ json_encode($abo) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}"></receipt-item-table>

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