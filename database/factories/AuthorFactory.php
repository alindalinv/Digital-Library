<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'bio' => $this->faker->paragraph(3),
            'nationality' => $this->faker->country(),
            'birth_date' => $this->faker->dateTimeBetween('-90 years', '-25 years')->format('Y-m-d'),
        ];
    }
}