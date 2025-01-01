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
        Schema::create('game_plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version');
            $table->string('category');
            $table->longText('description')->nullable();
            $table->json('eggs')->nullable();
            $table->string('download_url');
            $table->string('decompress_type')->nullable();
            $table->string('install_folder');
            $table->boolean('is_delete_all')->default(false);
            $table->json('delete_folder')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_plugins');
    }
};
