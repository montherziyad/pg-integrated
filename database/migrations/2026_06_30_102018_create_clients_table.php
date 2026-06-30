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
     Schema::create('clients', function (Blueprint $table) {

    $table->id();

    $table->string('client_code')->unique();

    $table->string('name');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('account_manager_id')
        ->nullable()
        ->constrained('users');

    $table->string('industry')->nullable();

    $table->string('email')->nullable();

    $table->string('phone')->nullable();

    $table->boolean('is_active')
        ->default(true);

    $table->timestamps();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
