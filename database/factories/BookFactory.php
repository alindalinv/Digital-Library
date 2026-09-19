<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'isbn' => $this->faker->unique()->isbn13(),
            'description' => $this->faker->paragraph(5),
            'category_id' => Category::inRandomOrder()->first()?->id
                ?? Category::factory(),
            'published_year' => $this->faker->numberBetween(1950, (int) date('Y')),
            'language' => $this->faker->randomElement(['en', 'kh', 'fr', 'es', 'de']),
            'pages' => $this->faker->numberBetween(80, 900),
            'price' => $this->faker->randomFloat(2, 5, 120),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_featured' => $this->faker->boolean(20),
            'status' => 'published',
        ];
    }
}