<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);
        $attributes = request()->validate([
            'body' => 'required',
        ]);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $validated = request()->validate([
            'body' => 'sometimes|required',
        ]);
        $task->update($validated);

        if(request('completed')){
            $task->complete();
        } else {
            $task->incomplete();
        }

        return redirect($project->path());
    }
}
