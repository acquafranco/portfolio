<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Own product or client project. Existing projects are own products.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('kind', 20)->default('product')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('kind');
        });
    }
};
