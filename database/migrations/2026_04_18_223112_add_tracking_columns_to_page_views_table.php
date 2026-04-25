<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('referer', 500)->nullable()->after('user_agent');     // d'où vient le visiteur
            $table->string('device_type', 20)->nullable()->after('referer');     // mobile / tablet / desktop
            $table->string('browser', 50)->nullable()->after('device_type');     // Chrome, Firefox...
            $table->string('os', 50)->nullable()->after('browser');              // Windows, iOS...
            $table->string('country', 100)->nullable()->after('os');             // pays (via IP)
            $table->boolean('is_new_visitor')->default(true)->after('country');  // nouveau ou récurrent
            $table->integer('session_duration')->nullable()->after('is_new_visitor'); // durée en secondes
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn([
                'referer', 'device_type', 'browser',
                'os', 'country', 'is_new_visitor', 'session_duration'
            ]);
        });
    }
};
