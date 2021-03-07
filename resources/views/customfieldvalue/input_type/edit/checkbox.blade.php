<div class="form-group row" id="form-group-{{ $customfieldvalue->key }}">
    @include($customfieldvalue->getBaseViewPath('edit.label'))
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" name="{{ $customfieldvalue->key }}" type="checkbox" value="{{ $customfieldvalue->id }}" id="{{ $customfieldvalue->key }}" {{ ($customfieldvalue->value ? 'checked="checked"' : '') }}>
        </div>
    </div>
</div>