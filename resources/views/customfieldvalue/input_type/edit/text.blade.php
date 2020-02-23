<div class="form-group" id="form-group-{{ $customfieldvalue->key }}">
    @include($customfieldvalue->getBaseViewPath('edit.label'))
    <input type="text" class="form-control {{ ($errors->has($customfieldvalue->key) ? 'is-invalid' : '') }}" id="{{ $customfieldvalue->key }}" name="{{ $customfieldvalue->key }}" value="{{ old($customfieldvalue->key) ?? $customfieldvalue->value }}">
    @if ($errors->has($customfieldvalue->key))
        <div class="invalid-feedback">
            {{ $errors->first($customfieldvalue->key) }}
        </div>
    @endif
</div>