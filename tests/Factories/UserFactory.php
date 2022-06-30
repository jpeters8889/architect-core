<?php

namespace Jpeters8889\Architect\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'username' => $this->faker->userName,
            'password' => Hash::make('password'),
            'active' => $this->faker->boolean,
            'level' => $this->faker->randomElement(['Member', 'Privileged', 'Admin']),
        ];
    }
}
