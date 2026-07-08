<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->timestamp('production_due_at')->nullable()->after('final_due_at');
            $table->foreignId('production_due_confirmed_by')->nullable()->after('production_due_at')->constrained('users')->nullOnDelete();
            $table->timestamp('production_due_confirmed_at')->nullable()->after('production_due_confirmed_by');
            $table->text('production_due_notes')->nullable()->after('production_due_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->dropForeign(['production_due_confirmed_by']);
            $table->dropColumn([
                'production_due_at',
                'production_due_confirmed_by',
                'production_due_confirmed_at',
                'production_due_notes',
            ]);
        });
    }
};
