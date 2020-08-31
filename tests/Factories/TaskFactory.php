<?php


namespace Tests\Factories;


use App\Project;
use App\Task;
use App\User;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    private ?Project $project = null;

    public static function new(): TaskFactory
    {
        return new self();
    }

    public function withProject(Project $project)
    {
        $clone = clone $this;

        $clone->project = $project;

        return $clone;
    }

    public function create(array $extra = []): Task
    {
        return Task::create($this->raw($extra));
    }

    public function raw(array $extra = []): array
    {
        $faker = FakerFactory::create();
        return $extra + [
            'body' => $faker->sentence,
            'completed' => false,
            'project_id' => $this->project->id ?? ProjectFactory::new()->create()->id
        ];
    }

}
