<?php

namespace App;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    use HasCompany;

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
            if (! $model->company_id)
            {
                $model->company_id = auth()->user()->company_id;
            }
            if (! $model->user_id)
            {
                $model->user_id = auth()->user()->id;
            }

            return true;
        });
    }

    protected $fillable = [
        'company_id',
        'text',
        'user_id',
    ];

    public function getTextAttribute($text)
    {
        return nl2br(\Purify::clean($text));
    }

    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
