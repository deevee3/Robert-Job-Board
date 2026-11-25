<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Job postings imported from the scraper store (jobs.db).
     * Named job_postings because `jobs` is Laravel's queue table.
     */
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('source')->index();
            $table->string('company')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->text('url')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('first_seen')->nullable()->index();
            $table->string('salary')->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
