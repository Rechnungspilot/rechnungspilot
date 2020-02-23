<form action="{{ url('/belege/artikel', $receiptitem->id) }}" method="POST" id="edit-item">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $receiptitem->name }}">
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="description">Beschreibung</label>
        <textarea class="form-control {{ ($errors->has('description') ? 'is-invalid' : '') }}" id="description" name="description" rows="3">{{ $receiptitem->description }}</textarea>
        @if ($errors->has('description'))
            <div class="invalid-feedback">
                {{ $errors->first('description') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="quantity">Menge</label>
        <input type="text" class="form-control {{ ($errors->has('quantity') ? 'is-invalid' : '') }}" id="quantity" name="quantity" value="{{ number_format($receiptitem->quantity, 2, ',', '') }}">
        @if ($errors->has('quantity'))
            <div class="invalid-feedback">
                {{ $errors->first('quantity') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="unit_id">Einheit</label>
        <select class="form-control {{ ($errors->has('unit_id') ? 'is-invalid' : '') }}" id="unit_id" name="unit_id">
            @foreach($units as $unit)
                <option value="{{ $unit->id}}" {{ $receiptitem->unit_id == $unit->id ? 'selected="selected"' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
            @endforeach
        </select>
        @if ($errors->has('unit_id'))
            <div class="invalid-feedback">
                {{ $errors->first('unit_id') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="unit_price">Stückpreis</label>
        <input type="text" pattern="(\d+(,\d{1,6})?)?" class="form-control {{ ($errors->has('unit_price') ? 'is-invalid' : '') }}" id="unit_price" name="unit_price" value="{{ $receiptitem->unitPriceFormated }}">
        @if ($errors->has('unit_price'))
            <div class="invalid-feedback">
                {{ $errors->first('unit_price') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="discount">Rabatt</label>
        <input type="text" class="form-control {{ ($errors->has('discount') ? 'is-invalid' : '') }}" id="discount" name="discount" value="{{ number_format($receiptitem->discount * 100, 1, ',', '') }}">
        @if ($errors->has('discount'))
            <div class="invalid-feedback">
                {{ $errors->first('discount') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="tax">USt.</label>
        <select class="form-control {{ ($errors->has('tax') ? 'is-invalid' : '') }}" id="tax" name="tax">
            <option value="0.19" {{ $receiptitem->tax == 0.19 ? 'selected="selected"' : '' }}>19%</option>
            <option value="0.07" {{ $receiptitem->tax == 0.07 ? 'selected="selected"' : '' }}>7%</option>
            <option value="0" {{ $receiptitem->tax == 0 ? 'selected="selected"' : '' }}>0%</option>
        </select>
        @if ($errors->has('tax'))
            <div class="invalid-feedback">
                {{ $errors->first('tax') }}
            </div>
        @endif
    </div>

</form>