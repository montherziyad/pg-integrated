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
        Schema::create('email_intakes', function (Blueprint $table) {
    $table->id();

    $table->string('source')->default('OUTLOOK');

    $table->string('message_id')->unique();
    $table->string('sender_email')->nullable();
    $table->string('sender_name')->nullable();

    $table->string('subject')->nullable();
    $table->longText('body')->nullable();

    $table->timestamp('received_at')->nullable();

    $table->boolean('has_attachments')->default(false);

    $table->enum('status', [
        'NEW',
        'REVIEWED',
        'CONVERTED_TO_JOB',
        'IGNORED',
        'FAILED'
    ])->default('NEW');

    $table->foreignId('creative_job_id')
        ->nullable()
        ->constrained('creative_jobs')
        ->nullOnDelete();

    $table->json('raw_payload')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_intakes');
    }
};
