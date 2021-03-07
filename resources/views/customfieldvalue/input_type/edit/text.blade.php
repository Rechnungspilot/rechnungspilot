<div class="form-group row" id="form-group-{{ $customfieldvalue->key }}">
    @include($customfieldvalue->getBaseViewPath('edit.label'))
    <div class="col-sm-8">
        <input type="text" class="form-control form-control-sm {{ ($errors->has($customfieldvalue->key) ? 'is-invalid' : '') }}" id="{{ $customfieldvalue->key }}" name="{{ $customfieldvalue->key }}" value="{{ old($customfieldvalue->key) ?? $customfieldvalue->value }}">
        @error($customfieldvalue->key))
            <div class="invalid-feedback">
                $message
            </div>
        @enderror
    </div>
</div>