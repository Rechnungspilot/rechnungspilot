<div class="form-group" id="form-group-{{ $customfieldvalue->key }}">
    @include($customfieldvalue->getBaseViewPath('edit.label'))
    <select class="form-control {{ ($errors->has($customfieldvalue->key) ? 'is-invalid' : '') }}" id="{{ $customfieldvalue->key }}" name="{{ $customfieldvalue->key }}">
        @foreach ($customfieldvalue->customfield->options as $key => $option)
            <option value="{{ $key }}" {{ $customfieldvalue->value == $key ? 'selected="selected"' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @if ($errors->has($customfieldvalue->key))
        <div class="invalid-feedback">
            {{ $errors->first($customfieldvalue->key) }}
        </div>
    @endif
</div>