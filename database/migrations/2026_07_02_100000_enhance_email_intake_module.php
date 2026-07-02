<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_intakes', function (Blueprint $table) {
            $table->string('conversation_id')->nullable()->after('message_id');
            $table->string('internet_message_id')->nullable()->after('conversation_id');
            $table->json('to_recipients')->nullable()->after('sender_name');
            $table->json('cc_recipients')->nullable()->after('to_recipients');
            $table->string('extracted_job_number')->nullable()->after('has_attachments');
            $table->boolean('validation_passed')->default(false)->after('extracted_job_number');
            $table->json('validation_errors')->nullable()->after('validation_passed');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->foreignId('reviewed_by')->nullable()->after('rejection_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->timestamp('accepted_at')->nullable()->after('reviewed_at');
            $table->timestamp('rejected_at')->nullable()->after('accepted_at');
        });

        Schema::create('email_intake_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_intake_id')->constrained()->cascadeOnDelete();
            $table->string('outlook_attachment_id')->nullable();
            $table->string('name');
            $table->string('content_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->boolean('is_inline')->default(false);
            $table->boolean('is_brief')->default(false);
            $table->string('storage_disk')->default('local');
            $table->text('storage_path')->nullable();
            $table->string('sha256', 64)->nullable();
            $table->timestamps();
        });

        Schema::create('email_intake_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_intake_id')->constrained()->cascadeOnDelete();
            $table->string('rule');
            $table->boolean('passed');
            $table->text('message')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });

        Schema::create('email_intake_traffic_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('outlook_email');
            $table->boolean('is_active')->default(true);
            $table->boolean('receives_notifications')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_intake_traffic_members');
        Schema::dropIfExists('email_intake_validations');
        Schema::dropIfExists('email_intake_attachments');

        Schema::table('email_intakes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn([
                'conversation_id',
                'internet_message_id',
                'to_recipients',
                'cc_recipients',
                'extracted_job_number',
                'validation_passed',
                'validation_errors',
                'rejection_reason',
                'reviewed_at',
                'accepted_at',
                'rejected_at',
            ]);
        });
    }
};
