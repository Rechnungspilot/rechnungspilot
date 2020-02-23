<form class="form-inline mb-5" action="{{ url('/belege/' . $id . '/artikel') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="item_id">Artikel hinzufügen</label>
        <select class="form-control mx-sm-3 {{ ($errors->has('item_id') ? 'is-invalid' : '') }}" id="item_id" name="item_id">
            @foreach($items as $item)
                <option value="{{ $item->id}}">{{ $item->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('item_id'))
            <div class="invalid-feedback">
                {{ $errors->first('item_id') }}
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Hinzufügen</button>

</form>