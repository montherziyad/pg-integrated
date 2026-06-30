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
        Schema::create('workflow_transitions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('from_stage_id')
                ->constrained('workflow_stages')
                ->cascadeOnDelete();

            $table->foreignId('to_stage_id')
                ->constrained('workflow_stages')
                ->cascadeOnDelete();

            $table->string('name')->nullable();

            $table->string('code')->unique();

            $table->boolean('requires_permission')->default(false);

            $table->string('required_permission')->nullable();

            $table->boolean('requires_comment')->default(false);

            $table->boolean('requires_file')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_transitions');
    }
};