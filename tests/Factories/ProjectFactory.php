<?php


namespace Tests\Factories;


use App\Project;
use App\User;
use Faker\Factory as FakerFactory;

class ProjectFactory extends Factory
{
    private ?User $owner = null;
    private int $withTasks = 0;

    public static function new(): ProjectFactory
    {
        return new self();
    }

    public function withOwner(User $user) : self
    {
        $clone = clone $this;

        $clone->owner = $user;

        return $clone;
    }

    public function withTasks(int $count)
    {
        $clone = clone $this;

        $clone->withTasks = $count;

        return $clone;
    }

    public function create(array $extra = []): Project
    {
        $project = Project::create($this->raw($extra));
        for($i=0;$i<$this->withTasks;$i++)
        {
            TaskFactory::new()->withProject($project)->create();
        }
        return $project;
    }

    public function raw(array $extra = []): array
    {
        $faker = FakerFactory::create();
        return $extra + [
            'title' => $faker->sentence(4),
            'description' => $faker->sentence(4),
            'notes' => $faker->paragraph,
            'owner_id' => $this->owner->id ?? UserFactory::new()->create()->id
        ];
    }
}

