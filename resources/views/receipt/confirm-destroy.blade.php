<div class="modal fade" tabindex="-1" role="dialog" id="confirm-delete">
    <div class="modal-dialog" role="document">
        <form action="{{ $route }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beleg löschen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Soll der Beleg wirklich gelöscht werden?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Beleg löschen</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </form>
    </div>
</div>