<?php

namespace App\Todos;

use App\Contacts\Contact;
use App\Item;
use App\Projects\Section;
use App\Receipts\Receipt;
use App\Time;
use App\Traits\HasComments;
use App\Traits\HasCompany;
use App\Traits\HasTags;
use App\Traits\HasUserfiles;
use App\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Todo extends Model
{
    use HasComments, HasCompany, HasTags, HasUserfiles;

    const PRIORITY_HIGH = 0;
    const PRIORITY_MEDIUM = 1;
    const PRIORITY_LOW = 2;

    const DEFAULT_PRIORITY = self::PRIORITY_MEDIUM;

    protected $appends = [
        'days',
        'endDate',
        'fullName',
        'isSameDay',
        'path',
        'startDate',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'company_id',
        'completed',
        'completed_at',
        'completer_id',
        'creator_id',
        'description',
        'duration',
        'end_at',
        'full_day',
        'item_id',
        'name',
        'note',
        'priority',
        'start_at',
        'todoable_id',
        'todoable_type',
        'user_id',
    ];

    protected $uri = '/aufgaben';

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! isset($model->creator_id))
            {
                $model->creator_id = Auth::id() ?? null;
            }

            return true;
        });
    }

    public function attach(Model $model, array $attributes = []) : Model
    {
        switch (get_class($model))
        {
            case \App\Contacts\Contact::class:
                return $this->attachContact($model, $attributes);
                break;
        }
    }

    protected function attachContact(Contact $contact, array $attributes) : Contact
    {
        $attributes = array_merge([
            'address' => $contact->billing_address,
            'company_id' => $this->company_id,
            'user_id' => auth()->user()->id,
        ], $attributes);

        $this->contacts()->attach($contact->id, $attributes);

        return $this->contacts()
            ->where('contact_id', $contact->id)
            ->where('todo_id', $this->id)
            ->with('pivot.user')
            ->first();
    }

    public function detach(Model $model)
    {
        switch (get_class($model))
        {
            case \App\Contacts\Contact::class: $this->contacts()->detach($model->id); break;
        }
    }

    public function addItem(Item $item) : self
    {
        $this->item_id = $item->id;

        return $this;
    }

    public function assignTo(User $teamMember) : self
    {
        $this->user_id = $teamMember->id;

        return $this;
    }

    public function recordTime() : Time
    {
        return Time::createFromTodo($this);
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function getStartDateAttribute()
    {
        return $this->start_at ? $this->start_at->format('Y-m-d') : '';
    }

    public function getEndDateAttribute()
    {
        return $this->end_at ? $this->end_at->format('Y-m-d') : '';
    }

    public function getIsSameDayAttribute()
    {
        if (is_null($this->attributes['end_at']))
        {
            return true;
        }

        return ($this->startDate == $this->endDate);
    }

    public function getDaysAttribute()
    {
        if ($this->isSameDay)
        {
            return [
                $this->startDate,
            ];
        }

        $days = [];
        $period = CarbonPeriod::create($this->start_at->startOfDay(), $this->end_at->endOfDay());
        foreach ($period as $key => $day) {
            $days[] = $day->format('Y-m-d');
        }

        return $days;
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?: '#' . $this->id;
    }

    public function getFullNameAttribute()
    {
        $pre = '';
        if (! array_key_exists('todoable_type', $this->attributes)) {
            return $pre;
        }

        switch ($this->attributes['todoable_type'])
        {
            case Receipt::class: $pre = $this->todoable->typeName . ' > ' . $this->todoable->name . ' > '; break;
            case Section::class: $pre = 'Projekt > ' . $this->todoable->project->name . ' > '; break;
            case Todo::class: $pre = 'Aufgabe > ' . $this->todoable->name . ' > '; break;
        }

        return $pre . $this->attributes['name'];
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = $value ?: null;
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    /**
     * depricated - nach und nach in teamMember ändern
     */
    public function team()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function teamMember() {
        return $this->team();
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function contacts()
    {
        return $this->belongsToMany(\App\Contacts\Contact::class)
            ->using(\App\Todos\Contacts::class)
            ->withPivot([
                'address',
                'user_id',
            ])->withTimestamps();
    }

    public function times()
    {
        return $this->morphMany('App\Time', 'timeable')
            ->with([
                'item',
                'user',
            ])->orderBy('start_at', 'DESC');
    }

    public function complete() : self
    {
        return $this->completedBy(auth()->user());
    }

    public function completedBy(User $user) : self
    {
        $this->attributes['completed'] = true;
        $this->attributes['completed_at'] = now();
        $this->attributes['completer_id'] = $user->id;

        return $this;
    }

    public function incomplete() : self
    {
        return $this->incompletedBy(auth()->user());
    }

    public function incompletedBy(User $user) : self
    {
        $this->attributes['completed'] = false;
        $this->attributes['completed_at'] = null;
        $this->attributes['completer_id'] = null;

        return $this;
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function isInProject()
    {
        return ! is_null($this->project);
    }

    public function getProjectAttribute()
    {
        if (! is_null($this->projectSection))
        {
            return $this->projectSection->project;
        }
        elseif (! is_null($this->todo) && ! is_null($this->todo->projectSection) )
        {
            return $this->todo->projectSection->project;
        }

        return null;
    }

    public function projectSection()
    {
        return $this->belongsTo('App\Projects\Section', 'todoable_id');
    }

    public function todo()
    {
        return $this->belongsTo('App\Todos\Todo', 'todoable_id');
    }

    public function subtodos()
    {
        return $this->morphMany('App\Todos\Todo', 'todoable');
    }

    public function todoable()
    {
        return $this->morphTo('todoable');
    }

    public function scopeCompleted(Builder $query, $completed) : Builder
    {
        if (is_null($completed) || $completed == -1)
        {
            return $query;
        }

        return $query->where('completed', $completed);
    }

    public function scopeAuthUsers(Builder $query, $bool) : Builder
    {
        if (! $bool)
        {
            return $query;
        }

        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeTeam(Builder $query, $id) : Builder
    {
        if (! $id)
        {
            return $query;
        }

        return $query->where('user_id', $id);
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('description', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function scopeContact(Builder $query, $id) : Builder
    {
        if (! $id)
        {
            return $query;
        }

        $query->whereHas('contacts', function ($query) use ($id) {
            $query->where('contact_id', $id);
        });
    }

    public function scopeReceipt(Builder $query, $id) : Builder
    {
        if (! $id)
        {
            return $query;
        }

        return $query->where('todoable_type', Receipt::class)
            ->where('todoable_id', $id);
    }


}
