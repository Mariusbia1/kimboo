<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->json('lieu_cours')->nullable()->after('experience_years');
            $table->text('a_propos_cours')->nullable()->after('lieu_cours');
            $table->string('video_url')->nullable()->after('a_propos_cours');
            $table->string('zone_deplacement')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn(['lieu_cours', 'a_propos_cours', 'video_url', 'zone_deplacement']);
        });
    }
};