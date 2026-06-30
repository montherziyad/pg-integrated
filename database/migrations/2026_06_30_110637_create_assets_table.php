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
       Schema::create('assets', function (Blueprint $table) {
    $table->id();

    $table->foreignId('creative_job_id')
        ->constrained('creative_jobs')
        ->cascadeOnDelete();

    $table->foreignId('uploaded_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('file_name');
    $table->string('original_name')->nullable();
    $table->string('file_type')->nullable();
    $table->string('mime_type')->nullable();
    $table->bigInteger('file_size')->default(0);

    $table->string('storage_type')->default('local'); 
    $table->text('storage_path');

    $table->integer('version')->default(1);

    $table->enum('asset_stage', [
        'BRIEF',
        'CONTENT',
        'DESIGN',
        'MOTION',
        'REVIEW',
        'FINAL',
        'SOURCE',
        'ARCHIVE'
    ])->default('DESIGN');

    $table->boolean('is_final')->default(false);

    $table->text('notes')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('assets');
    }
};
