<div class="form-group" id="form-group-{{ $customfieldvalue->key }}">
    <div class="form-check">
        <input class="form-check-input" name="{{ $customfieldvalue->key }}" type="checkbox" value="{{ $customfieldvalue->id }}" id="{{ $customfieldvalue->key }}" {{ ($customfieldvalue->value ? 'checked="checked"' : '') }}>
        @include($customfieldvalue->getBaseViewPath('edit.label'))
    </div>
</div>