<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <div>Individuelle Felder</div>
        @if(count($customfields))
            <div><i class="fas fa-fw fa-plus-square pointer" data-toggle="modal" data-target="#create-customfield"></i></div>
        @endif
    </div>
    <div class="card-body">
        @foreach($model->customfields as $value)
            @include($value->editViewPath, ['customfieldvalue' => $value])
        @endforeach
    </div>
</div>