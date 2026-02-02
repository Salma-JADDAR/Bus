<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role' => $this->faker->randomElement(['client', 'admin']),
            'phone' => '+2126' . $this->faker->numerify('########'),
        ];
    }

    public function client()
    {
        return $this->state([
            'role' => 'client',
        ]);
    }

    public function admin()
    {
        return $this->state([
            'role' => 'admin',
            'email' => 'admin@monocompagnie.com',
        ]);
    }
}