<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer les langues individuelles pour ne conserver que la catégorie unifiée "Langues"
        DB::table('categories')->whereIn('name', ['Anglais', 'Français', 'Espagnol', 'Allemand', 'Chinois', 'Arabe', 'Italien'])->delete();

        // Si des cours utilisaient ces catégories spécifiques, les basculer vers "Langues"
        DB::table('courses')->whereIn('category', ['Anglais', 'Français', 'Espagnol', 'Allemand', 'Chinois', 'Arabe', 'Italien'])->update(['category' => 'Langues']);

        // S'assurer que "Langues" existe bien dans les catégories
        if (!DB::table('categories')->where('name', 'Langues')->exists()) {
            DB::table('categories')->insert([
                'name' => 'Langues',
                'slug' => 'langues',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
