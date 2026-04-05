<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('teacher_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->text('bio')->nullable();
        $table->decimal('hourly_rate', 8, 2);
        $table->boolean('is_verified')->default(false);
        $table->boolean('first_course_free')->default(false);
        $table->float('rating')->default(0);
        $table->integer('reviews_count')->default(0);
        $table->string('experience_years')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
