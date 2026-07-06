<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('website_chat_sessions', function (Blueprint $table) {
            $table->id(); $table->uuid('visitor_token')->unique(); $table->string('locale',10)->default('en');
            $table->string('status')->default('open'); $table->string('name')->nullable(); $table->string('email')->nullable();
            $table->string('phone')->nullable(); $table->string('company')->nullable(); $table->timestamp('preferred_at')->nullable();
            $table->text('contact_notes')->nullable(); $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_message_at')->nullable(); $table->json('meta')->nullable(); $table->timestamps();
        });
        Schema::create('website_chat_messages', function (Blueprint $table) {
            $table->id(); $table->foreignId('session_id')->constrained('website_chat_sessions')->cascadeOnDelete();
            $table->string('role',20); $table->text('message'); $table->decimal('confidence',4,3)->nullable();
            $table->boolean('needs_human')->default(false); $table->json('source_refs')->nullable(); $table->json('meta')->nullable(); $table->timestamps();
        });
        Schema::create('website_chat_knowledge', function (Blueprint $table) {
            $table->id(); $table->text('question'); $table->text('answer')->nullable(); $table->string('status')->default('pending');
            $table->foreignId('source_message_id')->nullable()->constrained('website_chat_messages')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('reviewed_at')->nullable(); $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('website_chat_knowledge'); Schema::dropIfExists('website_chat_messages'); Schema::dropIfExists('website_chat_sessions');
    }
};
