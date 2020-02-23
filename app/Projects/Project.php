<?php

namespace App\Projects;

use App\Todos\Todo;
use App\Traits\HasCompany;
use App\Traits\HasUserfiles;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasCompany, HasUserfiles;

    protected $appends = [
        'path',
    ];

    protected $fillable = [
        'archived_at',
        'company_id',
        'completed_todos_count',
        'creator_id',
        'description',
        'due_at',
        'incompleted_todos_count',
        'name',
        'private',
        'project_group_id',
        'start_at',
        'todos_count',
    ];

    protected $uri = '/projekte';

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function sections()
    {
        return $this->hasMany('App\Projects\Section');
    }

    public function todos()
    {
        // TODO:
    }

    public function cache()
    {
        $sections = $this->sections()->with('todos.subtodos')->get();
        $todos_count = 0;
        $incompleted_todos_count = 0;
        $completed_todos_count = 0;
        foreach ($sections as $key => $section) {
            foreach ($section->todos as $key => $todo) {
                $todos_count++;
                if ($todo->completed)
                {
                    $completed_todos_count++;
                }
                else
                {
                    $incompleted_todos_count++;
                }
                foreach ($todo->subtodos as $key => $subtodo) {
                    $todos_count++;
                    if ($subtodo->completed)
                    {
                        $completed_todos_count++;
                    }
                    else
                    {
                        $incompleted_todos_count++;
                    }
                }
            }
        }

        $this->update([
            'todos_count' => $todos_count,
            'incompleted_todos_count' => $incompleted_todos_count,
            'completed_todos_count' => $completed_todos_count,
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public static function setup(int $companyId)
    {
        $group = Group::setup($companyId);

        self::setupSettings($companyId, $group);
        self::setupData($companyId, $group);
    }

    private static function setupData(int $companyId, Group $group)
    {
        $project = self::create([
            'company_id' => $companyId,
            'project_group_id' => $group->id,
            'creator_id' => 0,
            'name' => 'Stammdaten',
        ]);

        $section = $project->sections()->create([
            'company_id' => $companyId,
            'name' => 'Artikel',
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Artikel erstellen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $section = $project->sections()->create([
            'company_id' => $companyId,
            'name' => 'Kontakte',
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Kontakte anlegen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $project->cache();
    }

    private static function setupSettings(int $companyId, Group $group)
    {
        $project = self::create([
            'company_id' => $companyId,
            'project_group_id' => $group->id,
            'creator_id' => 0,
            'name' => 'Einstellungen',
        ]);

        $section = $project->sections()->create([
            'company_id' => $companyId,
            'name' => 'Allgemein',
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Angaben zu deiner Firma ausfüllen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo->subtodos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Kontakt',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo->subtodos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Bankverbindung',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo->subtodos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Sonstiges',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $section = $project->sections()->create([
            'company_id' => $companyId,
            'name' => 'Belege',
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Vorlage bearbeiten',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo->subtodos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Logo hochladen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Nummernkreise festlegen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $todo = $section->todos()->create([
            'company_id' => $companyId,
            'creator_id' => null,
            'name' => 'Textbausteine anpassen',
            'priority' => TODO::PRIORITY_MEDIUM,
            'user_id' => null,
        ]);

        $project->cache();
    }
}
