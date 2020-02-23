<?php

namespace App\Traits;

trait HasUserfiles
{
    public function userfiles()
    {
        return $this->morphMany('App\Userfile', 'fileable')
            ->with(['tags']);
    }
}