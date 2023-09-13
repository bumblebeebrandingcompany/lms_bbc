<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lead;
use App\Utils\Util;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $leads = Lead::all();
        if(count($leads) > 0) {
            $util = new Util();
            foreach ($leads as $lead) {
                $ref_num = $util->generateLeadRefNum($lead);
                $lead->ref_num = $ref_num;
                $lead->save();
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
