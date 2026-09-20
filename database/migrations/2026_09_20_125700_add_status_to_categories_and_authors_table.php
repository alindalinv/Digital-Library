<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('order');
        });
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_and_authors', function (Blueprint $table) {
            //
        });
    }
};
