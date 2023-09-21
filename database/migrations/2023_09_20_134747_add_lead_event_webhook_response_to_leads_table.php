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
        Schema::table('leads', function (Blueprint $table) {
            $table->longText('lead_event_webhook_response')
                ->after('lead_details')
                ->nullable();
            
            $table->string('sell_do_stage')
                ->after('lead_event_webhook_response')
                ->nullable();

            $table->string('sell_do_status')
                ->after('sell_do_stage')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
