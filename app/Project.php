<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function addTask(string $body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function recordActivity(string $description)
    {
        $this->activity()->create(compact('description'));
    }
}
