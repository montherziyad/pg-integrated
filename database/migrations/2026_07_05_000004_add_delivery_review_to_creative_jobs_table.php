<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->string('delivery_review_status')->default('draft')->after('final_delivery_path');
            $table->foreignId('delivery_reviewed_by')->nullable()->after('delivery_review_status')->constrained('users')->nullOnDelete();
            $table->timestamp('delivery_reviewed_at')->nullable()->after('delivery_reviewed_by');
            $table->timestamp('delivery_published_at')->nullable()->after('delivery_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->dropForeign(['delivery_reviewed_by']);
            $table->dropColumn([
                'delivery_review_status',
                'delivery_reviewed_by',
                'delivery_reviewed_at',
                'delivery_published_at',
            ]);
        });
    }
};
