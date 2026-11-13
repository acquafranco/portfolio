<?php

use Database\Seeders\ProjectSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Mobile screenshots now show each role logged in, starting with a
     * dashboard. Galleries that still hold only the seeded images are
     * replaced by the current seed gallery; any gallery with an image
     * uploaded from /admin is left untouched.
     */
    public function up(): void
    {
        foreach (ProjectSeeder::definitions() as $slug => $definition) {
            $project = DB::table('projects')->where('slug', $slug)->first();

            if ($project === null || empty($definition['gallery'])) {
                continue;
            }

            $current = json_decode((string) $project->gallery, true) ?: [];
            $prefix = "projects/{$slug}-gallery-";
            $paths = array_filter(array_merge(array_column($current, 'image'), array_column($current, 'image_small')));

            foreach ($paths as $path) {
                if (! str_starts_with($path, $prefix)) {
                    continue 2;
                }
            }

            $gallery = ProjectSeeder::copyGalleryToStorage($definition['gallery'], $slug);
            $kept = array_filter(array_merge(array_column($gallery, 'image'), array_column($gallery, 'image_small')));

            DB::table('projects')->where('id', $project->id)->update([
                'gallery' => json_encode($gallery, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);

            Storage::disk('public')->delete(array_values(array_diff($paths, $kept)));
        }
    }

    public function down(): void
    {
        //
    }
};
