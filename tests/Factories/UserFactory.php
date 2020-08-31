<?php


namespace Tests\Factories;


use App\Project;
use App\User;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public static function new(): UserFactory
    {
        return new self();
    }

    public function create(array $extra = []): User
    {
        $faker = FakerFactory::create();
        return User::create(
            $extra + [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        );
    }
}
