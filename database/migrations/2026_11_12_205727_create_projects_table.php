<?php

use Database\Seeders\ProjectSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Projects shown on the portfolio, managed from /admin. The current
     * projects (Ascento, GULA, DOMUS) are imported once from
     * database/seeders/projects/*.php; existing rows are never overwritten.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('name', 80);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true)->index();
            $table->string('status', 20);
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('url')->nullable();
            $table->string('repository')->nullable();
            $table->string('accent', 7)->default('#FF6A1A');
            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('cover_small_path')->nullable();
            $table->json('stack');
            $table->json('content');
            $table->json('links')->nullable();
            $table->timestamps();
        });

        (new ProjectSeeder)->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
