<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('contact_person')->nullable()->after('company_name');
            $table->string('country')->nullable()->after('industry');
            $table->string('city')->nullable()->after('country');
            $table->string('website')->nullable()->after('city');
            $table->string('avatar_path')->nullable()->after('phone');
            $table->text('company_profile')->nullable()->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'contact_person',
                'country',
                'city',
                'website',
                'avatar_path',
                'company_profile',
            ]);
        });
    }
};
