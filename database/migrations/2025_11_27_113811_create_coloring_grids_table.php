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
        Schema::create('coloring_grids', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('original_image_path');
            $table->string('processed_image_path')->nullable();
            $table->integer('grid_width')->default(30);
            $table->integer('grid_height')->default(30);
            $table->integer('color_count')->default(8);
            $table->json('grid_data')->nullable();
            $table->json('color_palette')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coloring_grids');
    }
};
