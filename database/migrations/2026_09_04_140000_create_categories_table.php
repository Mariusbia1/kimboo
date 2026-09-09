<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $defaultCategories = [
            'Mathématiques',
            'Physique-Chimie',
            'SVT',
            'Philosophie',
            'Histoire-Géographie',
            'Informatique',
            'Sciences',
            'Langues',
            'Économie',
            'Comptabilité',
            'Musique',
            'Arts',
            'Cuisine',
            'Sport',
        ];

        $now = now();
        $inserts = array_map(function ($cat) use ($now) {
            return [
                'name' => $cat,
                'slug' => Str::slug($cat),
                'created_by_user_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $defaultCategories);

        DB::table('categories')->insertOrIgnore($inserts);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
