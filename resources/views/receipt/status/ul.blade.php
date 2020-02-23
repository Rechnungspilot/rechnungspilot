<h5>Verlauf</h5>
<div class="list-group mb-5">
    @foreach($statuses as $key => $status)
        <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ $status->name }}</h5>
                <div class="d-flex align-items-center">
                    <small class="mr-1">{{ $status->date->format('d.m.Y') }}</small>
                    @if ($key == 0 && get_class($status) != 'App\Receipts\Statuses\Draft')
                        <form action="/belege/status/{{ $status->id }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-link btn-xs pointer py-0" title="Löschen"><i class="fas fa-trash text-danger"></i></button>
                        </form>
                    @endif
                </div>
            </div>
            @if($status->description)
                <p class="mb-1">{!! $status->description !!}</p>
            @endif
            @if ($status->user_id)
                <small>{{ $status->user->name }}</small>
            @endif
        </div>
    @endforeach
</div>