<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->index('is_featured');
            $table->index('is_verified');
            $table->index('rating');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->index('status');
            $table->index('category');
            $table->index('price_per_hour');
        });

        Schema::table('page_views', function (Blueprint $table) {
            $table->index('viewed_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['rating']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['category']);
            $table->dropIndex(['price_per_hour']);
        });

        Schema::table('page_views', function (Blueprint $table) {
            $table->dropIndex(['viewed_at']);
            $table->dropIndex(['user_id']);
        });
    }
};
