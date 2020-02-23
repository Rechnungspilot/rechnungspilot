<?php

namespace App\Projects;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasCompany;

    protected $appends = [
        'path',
    ];

    protected $fillable = [
        'company_id',
        'name',
        'project_id',
        'sort',
    ];

    protected $table = 'project_sections';

    public function getPathAttribute()
    {
        return '/projekte/' . $this->project_id . '/abschnitte/' . $this->id;
    }

    public function project()
    {
        return $this->belongsTo('App\Projects\Project');
    }

    public function todos()
    {
        return $this->morphMany('App\Todos\Todo', 'todoable');
    }

}
