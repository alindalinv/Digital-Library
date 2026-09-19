<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn', 20)->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            // publisher_id as a plain nullable column (no FK yet)
            $table->unsignedBigInteger('publisher_id')->nullable();

            $table->year('published_year')->nullable();
            $table->string('language', 50)->default('en');
            $table->integer('pages')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('published');
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('is_featured');
            $table->index('status');
            $table->index('publisher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};