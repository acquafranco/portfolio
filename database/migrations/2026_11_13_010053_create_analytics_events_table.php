<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * First-party analytics: page views and key clicks (CV, WhatsApp, email).
     * No IPs and no cookies: visitors are counted with a hash that rotates
     * every day, so nobody can be followed across days.
     */
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('path', 255);
            $table->string('locale', 5)->nullable();
            $table->string('referrer_host', 255)->nullable();
            $table->char('country', 2)->nullable();
            $table->string('device', 10);
            $table->char('visitor_hash', 16);
            $table->timestamp('created_at')->index();

            $table->index(['name', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
