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
        Schema::table('coloring_grids', function (Blueprint $table) {
            $table->string('aspect_ratio_mode')->default('manual')->after('numbering_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coloring_grids', function (Blueprint $table) {
            $table->dropColumn('aspect_ratio_mode');
        });
    }
};
