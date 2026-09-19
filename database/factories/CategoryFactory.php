<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->sentence(12),
            'parent_id' => null,
            'order' => 0,
        ];
    }
}