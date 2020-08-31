<?php

namespace App\Observers;

use App\Activity;
use App\Task;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param Task $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->recordActivity('created_task');
    }

    /**
     * Handle the task "updated" event.
     *
     * @param Task $task
     * @return void
     */
    public function updated(Task $task)
    {
        if($task->isDirty('completed')){;
            $task->recordActivity($task->completed ? 'completed_task' : 'incompleted_task');
        }
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->project->recordActivity('deleted_task');
    }
}
