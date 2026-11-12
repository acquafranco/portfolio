<?php

namespace Tests;

use App\Models\Project;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    /**
     * Fake the "public" disk right after the app boots, before migrations run,
     * so the projects migration (which copies seed images) never writes to
     * storage/app/public.
     */
    protected function refreshApplication(): void
    {
        parent::refreshApplication();

        Storage::fake('public');
    }

    /**
     * The in-memory database is migrated once and reused between tests, but
     * the fake disk is emptied for each test: put the seed images back.
     */
    protected function setUp(): void
    {
        parent::setUp();

        foreach (ProjectSeeder::definitions() as $slug => $definition) {
            foreach (['logo', 'cover', 'cover_small'] as $key) {
                $source = $definition[$key] ?? null;

                if ($source !== null && ! Project::isUploaded($source) && is_file(public_path($source))) {
                    Storage::disk('public')->put('projects/'.$slug.'-'.basename($source), (string) file_get_contents(public_path($source)));
                }
            }
        }
    }
}
