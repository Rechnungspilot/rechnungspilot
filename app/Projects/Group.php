<?php

namespace App\Projects;

use App\Projects\Project;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasCompany;

    protected $appends = [
        'path',
    ];

    protected $fillable = [
        'company_id',
        'name',
        'sort',
    ];

    protected $table = 'project_groups';

    protected $uri = '/projektgruppen';

    public function projects()
    {
        return $this->hasMany(Project::class, 'project_group_id');
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public static function setup(int $companyId) : self
    {
        return self::create([
            'company_id' => $companyId,
            'name' => 'Rechnungspilot einrichten',
        ]);
    }
}
