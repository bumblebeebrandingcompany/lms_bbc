<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $leads = DB::table('leads')
                ->whereNotNull('lead_event_webhook_response')
                ->whereNull('sell_do_lead_created_at')
                ->select(['id', 'lead_event_webhook_response'])
                ->get();

        foreach ($leads as $lead) {
            $req_data = json_decode($lead->lead_event_webhook_response, true);
            $details['sell_do_lead_created_at'] = $req_data['payload']['recieved_on'] ?? null;
            DB::table('leads')
            ->where('id', $lead->id)
            ->update($details);
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
