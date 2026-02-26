<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversion_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_token', 64)->nullable()->index();
            $table->string('tool_key', 50)->index();
            $table->string('status', 30)->default('pending')->index();
            $table->unsignedInteger('file_count');
            $table->unsignedBigInteger('total_size_bytes');
            $table->json('options')->nullable();
            $table->string('queue_job_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('output_disk')->nullable();
            $table->string('output_path')->nullable();
            $table->string('output_name')->nullable();
            $table->string('output_mime')->nullable();
            $table->unsignedBigInteger('output_size_bytes')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversion_tasks');
    }
};
