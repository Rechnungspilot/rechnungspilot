<div class="form-group row" id="form-group-{{ $customfieldvalue->key }}">
    @include($customfieldvalue->getBaseViewPath('edit.label'))
    <div class="col-sm-8">
        <select class="form-control form-control-sm @error($customfieldvalue->key) is-invalid @enderror" id="{{ $customfieldvalue->key }}" name="{{ $customfieldvalue->key }}">
            @foreach ($customfieldvalue->customfield->options as $key => $option)
                <option value="{{ $key }}" {{ $customfieldvalue->value == $key ? 'selected="selected"' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
        @error($customfieldvalue->key)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>