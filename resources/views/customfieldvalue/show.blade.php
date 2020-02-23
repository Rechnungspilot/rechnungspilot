@foreach($model->customfields as $value)
    @include($value->showViewPath, ['customfieldvalue' => $value])
@endforeach