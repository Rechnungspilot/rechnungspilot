<div class="modal fade" tabindex="-1" role="dialog" id="create-customfield">
    <div class="modal-dialog" role="document">
        <form action="{{ route('customfieldvalue.store', ['type' => substr($model->uri, 1), 'model' => $model->id]) }}" id="form-create-customfield" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Individuelle Felder hinzufügen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach($customfields as $customfield)
                        <div class="form-check">
                            <input class="form-check-input" name="custom_field_ids[]" type="checkbox" value="{{ $customfield->id }}" id="{{ $customfield->key }}">
                            <label class="form-check-label" for="{{ $customfield->key }}">
                                 {{ $customfield ->name }} <span class="text-muted">{{ $customfield ->inputTypeName }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Hinzufügen</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </form>
    </div>
</div>