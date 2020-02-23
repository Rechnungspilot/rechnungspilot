<?php

namespace App\Traits;

trait HasComments
{
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}