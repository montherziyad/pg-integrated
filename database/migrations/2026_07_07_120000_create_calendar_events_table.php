<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->date('event_date');
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('type')->default('planning');
            $table->string('country')->default('Saudi Arabia');
            $table->json('audience')->nullable();
            $table->json('roles')->nullable();
            $table->json('team_ids')->nullable();
            $table->text('note')->nullable();
            $table->text('action')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['event_date', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
