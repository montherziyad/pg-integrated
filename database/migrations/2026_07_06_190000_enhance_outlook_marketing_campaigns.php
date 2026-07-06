<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->text('objective')->nullable()->after('name');
            $table->string('country')->nullable()->after('objective');
            $table->string('industry')->nullable()->after('country');
            $table->string('email_subject')->nullable()->after('status');
            $table->string('cta_url', 1000)->nullable()->after('message_template');
            $table->foreignId('approved_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        DB::table('marketing_campaigns')->where('channel', 'email')->update(['channel' => 'outlook_email']);
    }

    public function down(): void
    {
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['objective', 'country', 'industry', 'email_subject', 'cta_url', 'approved_at']);
        });
    }
};
