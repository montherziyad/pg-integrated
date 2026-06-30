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
        Schema::create('workflow_stages', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('code')->unique();

            $table->text('description')->nullable();

            $table->unsignedInteger('sort_order')->default(1);

            $table->string('color', 20)->default('#64748B');

            $table->string('icon')->nullable();

            $table->boolean('is_start')->default(false);

            $table->boolean('is_end')->default(false);

            $table->boolean('requires_approval')->default(false);

            $table->boolean('allow_file_upload')->default(true);

            $table->boolean('allow_comments')->default(true);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_stages');
    }
};