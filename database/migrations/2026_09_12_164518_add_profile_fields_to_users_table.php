<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('gender', 20)->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('gender');

            $table->string('organization', 255)
                ->nullable()
                ->after('date_of_birth');

            $table->string('job_title', 255)
                ->nullable()
                ->after('organization');

            $table->text('bio')
                ->nullable()
                ->after('job_title');

            $table->text('address')
                ->nullable()
                ->after('bio');

            $table->string('facebook', 255)
                ->nullable()
                ->after('address');

            $table->string('twitter', 255)
                ->nullable()
                ->after('facebook');

            $table->string('linkedin', 255)
                ->nullable()
                ->after('twitter');

            $table->string('instagram', 255)
                ->nullable()
                ->after('linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'gender',
                'date_of_birth',
                'organization',
                'job_title',
                'bio',
                'address',
                'facebook',
                'twitter',
                'linkedin',
                'instagram',
            ]);
        });
    }
};