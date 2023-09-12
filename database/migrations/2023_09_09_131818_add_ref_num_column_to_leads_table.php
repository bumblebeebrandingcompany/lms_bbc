<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lead;
use App\Models\Project;
use App\Utils\Util;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('ref_num')
                ->after('id')
                ->nullable();
        });
        
        $projects = Project::all();
        if(count($projects) > 0) {
            $util = new Util();
            foreach ($projects as $project) {
                $leads = Lead::where('project_id', $project->id)
                            ->get();
                if(count($leads) > 0) {
                    foreach ($leads as $lead) {
                        $ref_num = $util->generateLeadRefNum($project->id);
                        $lead->ref_num = $ref_num;
                        $lead->save();
                    }
                }
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
