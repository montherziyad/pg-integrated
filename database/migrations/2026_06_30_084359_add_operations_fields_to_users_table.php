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
     Schema::table('users', function (Blueprint $table) {

    $table->string('employee_no')->nullable()->after('id');

    $table->foreignId('branch_id')
          ->nullable()
          ->constrained('branches')
          ->nullOnDelete();

    $table->foreignId('team_id')
          ->nullable()
          ->constrained('teams')
          ->nullOnDelete();

    $table->foreignId('role_id')
          ->nullable()
          ->constrained('roles')
          ->nullOnDelete();

    $table->string('job_title')->nullable();

    $table->string('mobile')->nullable();

    $table->integer('capacity_hours')->default(8);

    $table->boolean('is_active')->default(true);

});
    }

    /**
     * Reverse the migrations.
     */
  public function down(): void
{
    Schema::table('users', function (Blueprint $table) {

        $table->dropForeign(['branch_id']);
        $table->dropForeign(['team_id']);
        $table->dropForeign(['role_id']);

        $table->dropColumn([
            'employee_no',
            'branch_id',
            'team_id',
            'role_id',
            'job_title',
            'mobile',
            'capacity_hours',
            'is_active'
        ]);

    });
}
};
