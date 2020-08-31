<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];
    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return $this->project->path() . '/tasks/' . $this->getKey();
    }

    public function complete()
    {
        return $this->update(['completed' => true]);
    }

    public function incomplete()
    {
        return $this->update(['completed' => false]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity(string $description)
    {
        $this->activity()->create([
            'description' => $description,
            'project_id' => $this->project->id
        ]);
    }
}
