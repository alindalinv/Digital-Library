<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $authors = Author::all();

        if ($categories->isEmpty()) {
            $categories = Category::factory()->count(10)->create();
        }

        if ($authors->isEmpty()) {
            $authors = Author::factory()->count(20)->create();
        }

        Book::factory()
            ->count(100)
            ->create([
                'category_id' => fn () => $categories->random()->id,
            ])
            ->each(function (Book $book) use ($authors) {
                // Attach 1–3 random authors to each book
                $book->authors()->attach(
                    $authors->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
    }
}