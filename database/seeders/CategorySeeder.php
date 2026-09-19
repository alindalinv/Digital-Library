<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiction', 'description' => 'Novels, short stories, and literary works.'],
            ['name' => 'Non-Fiction', 'description' => 'Biographies, essays, and factual works.'],
            ['name' => 'Science & Technology', 'description' => 'Physics, biology, computing, and engineering.'],
            ['name' => 'History', 'description' => 'Historical accounts, biographies, and analysis.'],
            ['name' => 'Business & Economics', 'description' => 'Management, marketing, finance, and economics.'],
            ['name' => 'Children & Young Adult', 'description' => 'Books for young readers.'],
            ['name' => 'Education & Reference', 'description' => 'Textbooks, dictionaries, and reference materials.'],
            ['name' => 'Arts & Culture', 'description' => 'Art, music, film, and cultural studies.'],
            ['name' => 'Health & Wellness', 'description' => 'Medicine, fitness, nutrition, and self-help.'],
            ['name' => 'Religion & Philosophy', 'description' => 'Religious texts, philosophy, and ethics.'],
        ];

        foreach ($categories as $index => $data) {
            Category::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'description' => $data['description'],
                    'order' => $index,
                ]
            );
        }
    }
}