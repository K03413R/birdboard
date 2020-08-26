<?php


namespace Tests\WorldArrangements;


use App\Project;
use App\Task;
use App\User;
use Illuminate\Support\Collection;

class ProjectFactory
{
    public $user = null;
    public $task_count = 0;

    public function ownedBy(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function withTasks(int $count)
    {
        $this->task_count = $count;
        return $this;
    }

    public function create()
    {
        $project = factory(Project::class)->create([
            'owner_id' => $this->user ?? factory(User::class)->create()
        ]);

        factory(Task::class, $this->task_count)->create([
            'project_id' => $project->getKey()
        ]);

        return $project;
    }

    public function createMany(int $count)
    {
        $ret = new Collection();
        foreach(range(1,$count) as $index) {
            $ret->push($this->create());
        }
        return $ret;
    }


}
