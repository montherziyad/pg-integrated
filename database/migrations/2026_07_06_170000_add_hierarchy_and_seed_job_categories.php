<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('job_categories')->nullOnDelete();
            $table->string('default_team')->nullable()->after('description');
            $table->decimal('estimated_hours', 8, 2)->default(0)->after('default_team');
            $table->boolean('requires_approval')->default(true)->after('estimated_hours');
        });

        $groups = [
            ['Creative & Branding', 'CREATIVE', 'Creative', [
                ['Brand Strategy', 'CREATIVE_BRAND_STRATEGY'],
                ['Visual Identity', 'CREATIVE_VISUAL_IDENTITY'],
                ['Key Visual', 'CREATIVE_KEY_VISUAL'],
                ['Graphic Design', 'CREATIVE_GRAPHIC_DESIGN'],
                ['Packaging', 'CREATIVE_PACKAGING'],
                ['Adaptation', 'CREATIVE_ADAPTATION'],
            ]],
            ['Digital & Social Media', 'DIGITAL', 'Digital', [
                ['Content Calendar', 'DIGITAL_CONTENT_CALENDAR'],
                ['Social Posts', 'DIGITAL_SOCIAL_POSTS'],
                ['Community Management', 'DIGITAL_COMMUNITY'],
                ['Digital Campaign', 'DIGITAL_CAMPAIGN'],
                ['Influencer Campaign', 'DIGITAL_INFLUENCER'],
            ]],
            ['Motion & Production', 'PRODUCTION', 'Motion & Production', [
                ['Motion Graphics', 'PRODUCTION_MOTION'],
                ['Animation', 'PRODUCTION_ANIMATION'],
                ['TV Commercial', 'PRODUCTION_TVC'],
                ['Corporate Video', 'PRODUCTION_CORPORATE_VIDEO'],
                ['Photography', 'PRODUCTION_PHOTOGRAPHY'],
                ['Video Editing', 'PRODUCTION_EDITING'],
            ]],
            ['Media', 'MEDIA', 'Media', [
                ['Media Planning', 'MEDIA_PLANNING'],
                ['Media Buying', 'MEDIA_BUYING'],
                ['Outdoor Campaign', 'MEDIA_OUTDOOR'],
                ['TV Advertising', 'MEDIA_TV'],
                ['Digital Media', 'MEDIA_DIGITAL'],
            ]],
            ['Events & Activation', 'EVENTS', 'Events', [
                ['Brand Activation', 'EVENTS_ACTIVATION'],
                ['Corporate Event', 'EVENTS_CORPORATE'],
                ['Exhibition', 'EVENTS_EXHIBITION'],
                ['Booth Design', 'EVENTS_BOOTH'],
                ['Internal Campaign', 'EVENTS_INTERNAL'],
            ]],
            ['Strategy & Content', 'STRATEGY', 'Strategy & Content', [
                ['Campaign Strategy', 'STRATEGY_CAMPAIGN'],
                ['Brand Narrative', 'STRATEGY_NARRATIVE'],
                ['Copywriting', 'STRATEGY_COPYWRITING'],
                ['Arabic Content', 'STRATEGY_ARABIC'],
                ['English Content', 'STRATEGY_ENGLISH'],
            ]],
            ['Printing & Production', 'PRINT', 'Production', [
                ['Brochure', 'PRINT_BROCHURE'],
                ['Catalogue', 'PRINT_CATALOGUE'],
                ['POS Material', 'PRINT_POS'],
                ['Packaging Production', 'PRINT_PACKAGING'],
                ['Large Format Printing', 'PRINT_LARGE_FORMAT'],
            ]],
            ['Client Service', 'CLIENT_SERVICE', 'Client Service', [
                ['Quotation', 'CLIENT_QUOTATION'],
                ['Brief Review', 'CLIENT_BRIEF_REVIEW'],
                ['Client Request', 'CLIENT_REQUEST'],
                ['Presentation', 'CLIENT_PRESENTATION'],
                ['Final Delivery', 'CLIENT_FINAL_DELIVERY'],
            ]],
            ['Internal Work', 'INTERNAL', 'Internal', [
                ['PG Internal', 'INTERNAL_PG'],
                ['MediaZone', 'INTERNAL_MEDIAZONE'],
                ['HR', 'INTERNAL_HR'],
                ['Marketing', 'INTERNAL_MARKETING'],
                ['Business Development', 'INTERNAL_BD'],
            ]],
        ];

        foreach ($groups as [$name, $code, $team, $children]) {
            DB::table('job_categories')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $name,
                    'parent_id' => null,
                    'description' => $name.' services and jobs.',
                    'default_team' => $team,
                    'estimated_hours' => 0,
                    'requires_approval' => true,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $parentId = DB::table('job_categories')->where('code', $code)->value('id');

            foreach ($children as [$childName, $childCode]) {
                DB::table('job_categories')->updateOrInsert(
                    ['code' => $childCode],
                    [
                        'name' => $childName,
                        'parent_id' => $parentId,
                        'description' => $childName.' jobs.',
                        'default_team' => $team,
                        'estimated_hours' => 0,
                        'requires_approval' => true,
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn(['default_team', 'estimated_hours', 'requires_approval']);
        });
    }
};
