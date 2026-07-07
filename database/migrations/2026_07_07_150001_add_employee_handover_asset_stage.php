<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE assets DROP CONSTRAINT IF EXISTS assets_asset_stage_check");
        DB::statement("ALTER TABLE assets ADD CONSTRAINT assets_asset_stage_check CHECK (asset_stage::text = ANY (ARRAY['BRIEF','CONTENT','DESIGN','MOTION','REVIEW','EMPLOYEE_HANDOVER','FINAL','SOURCE','ARCHIVE']))");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE assets DROP CONSTRAINT IF EXISTS assets_asset_stage_check");
        DB::statement("ALTER TABLE assets ADD CONSTRAINT assets_asset_stage_check CHECK (asset_stage::text = ANY (ARRAY['BRIEF','CONTENT','DESIGN','MOTION','REVIEW','FINAL','SOURCE','ARCHIVE']))");
    }
};
