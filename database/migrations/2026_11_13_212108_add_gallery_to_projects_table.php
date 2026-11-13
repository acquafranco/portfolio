<?php

use Database\Seeders\ProjectSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Desktop and mobile screenshots per project. The seeded projects get the
     * gallery from database/seeders/projects/*.php when they have none yet,
     * and their original cover is replaced by the new one only if it was
     * never changed from /admin. Images are copied to the "public" disk, like
     * the ones imported by create_projects_table.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('cover_small_path');
        });

        foreach (ProjectSeeder::definitions() as $slug => $definition) {
            $project = DB::table('projects')->where('slug', $slug)->first();

            if ($project === null) {
                continue;
            }

            $updates = [];

            if (empty(json_decode((string) $project->gallery, true)) && ! empty($definition['gallery'])) {
                $gallery = ProjectSeeder::copyGalleryToStorage($definition['gallery'], $slug);
                $updates['gallery'] = json_encode($gallery, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $originalCovers = ["projects/{$slug}-cover.webp", "images/projects/{$slug}/cover.webp"];

            if (in_array($project->cover_path, $originalCovers, true) && isset($definition['cover'])) {
                $updates['cover_path'] = ProjectSeeder::copyToStorage($definition['cover'], $slug);
                $updates['cover_small_path'] = ProjectSeeder::copyToStorage($definition['cover_small'] ?? null, $slug);

                Storage::disk('public')->delete(["projects/{$slug}-cover.webp", "projects/{$slug}-cover-720.webp"]);
            }

            if ($updates !== []) {
                DB::table('projects')->where('id', $project->id)->update($updates);
            }
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
    }
};
