<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->boolean('is_cp_source')
                ->after('name')
                ->comment('to be used when cp is adding lead')
                ->default(0);

            $table->text('source_field1_description')
                ->after('source_field1')
                ->comment('description for source_field1')
                ->nullable();

            $table->text('source_field2_description')
                ->after('source_field2')
                ->comment('description for source_field2')
                ->nullable();

            $table->text('source_field3_description')
                ->after('source_field3')
                ->comment('description for source_field3')
                ->nullable();

            $table->text('source_field4_description')
                ->after('source_field4')
                ->comment('description for source_field4')
                ->nullable();
        });

        $projects = DB::table('projects')->get();

        if(count($projects) > 0) {
            foreach ($projects as $project) {
                DB::table('sources')->insert([
                    'name' => 'Channel Partner',
                    'is_cp_source' => 1,
                    'name' => 'Channel Partner',
                    'webhook_secret' => (string)Str::uuid(),
                    'project_id' => $project->id,
                    'created_at' => Carbon::today()->toDateTimeString(),
                    'updated_at' => Carbon::today()->toDateTimeString()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
