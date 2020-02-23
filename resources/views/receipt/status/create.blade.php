<input type="hidden" name="type" value="{{ get_class($status) }}">

<div class="form-group">
    <label for="date">Datum</label>
    <input type="text" class="form-control" id="date" name="date" value="{{ now()->format('d.m.Y') }}">
</div>

@foreach($status->dataAttributes as $key => $attribute)
    @if (array_key_exists('type', $attribute) && $attribute['type'] == 'textarea')
        <div class="form-group">
            <label for="{{ $key }}">{{ $attribute['label'] }}</label>
            <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" rows="10">{{ $attribute['value'] }}</textarea>
        </div>
    @elseif(array_key_exists('type', $attribute) && $attribute['type'] == 'checkbox')
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="{{ $key }}" name="{{ $key }}" value="{{ $attribute['value'] }}" <?php echo ($attribute['checked'] ? 'checked="checked' : ''); ?>>
            <label for="{{ $key }}">{{ $attribute['label'] }}</label>
        </div>
    @elseif(array_key_exists('type', $attribute) && $attribute['type'] == 'checkboxes' && count($attribute['value']))
        <h6>{{ $attribute['label'] }}</h6>
        @foreach($attribute['value'] as $option)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="{{ $key . $option['value'] }}" name="{{ $key }}[]" value="{{ $option['value'] }}" <?php echo ($option['checked'] ? 'checked="checked' : ''); ?>>
                <label for="{{ $key . $option['value'] }}">{{ $option['label'] }}</label>
            </div>
        @endforeach
    @elseif(array_key_exists('type', $attribute) && $attribute['type'] == 'select')
        <div class="form-group">
            <label for="{{ $key }}">{{ $attribute['label'] }}</label>
            <select class="form-control" id="{{ $key }}" name="{{ $key }}">
                @foreach($attribute['options'] as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="form-group">
            <label for="{{ $key }}">{{ $attribute['label'] }}</label>
            <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $attribute['value'] }}">
            @if (isset($attribute['small']) && $attribute['small'])
                <small>{{ $attribute['small'] }}</small>
            @endif
        </div>
    @endif
@endforeach