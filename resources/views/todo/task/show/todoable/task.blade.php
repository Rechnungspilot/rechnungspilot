<div class="card mb-3">
    <div class="card-header">
        Verknüpfung
    </div>
    <div class="card-body">
        <a href="{{ $todo->todoable->path }}">{{ $todo->todoable->name }}</a>
    </div>
</div>