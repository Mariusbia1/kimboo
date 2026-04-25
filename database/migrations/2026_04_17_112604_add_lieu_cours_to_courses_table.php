<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('lieu_cours')->nullable()->after('format');
            $table->string('zone_deplacement')->nullable()->after('lieu_cours');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['lieu_cours', 'zone_deplacement']);
        });
    }
};
