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
        Schema::create('call_activities', function (Blueprint $table) {
            $table->id();
            $table->string('id_sell_do')->nullable();
            $table->string('booking_detail_id')->nullable();
            $table->string('booking_detail_id')->nullable();
            $table->string('c2c')->nullable();
            $table->string('called_on')->nullable();
            $table->string('campaign_info_name')->nullable();
            $table->string('campaign_info_id')->nullable();
            $table->string('campaign_info_medium_type')->nullable();
            $table->string('campaign_info_medium_value')->nullable();
            $table->string('campaign_info_source')->nullable();
            $table->string('campaign_info_sub_source')->nullable();
            $table->dateTime('campaign_info_when')->nullable();
            $table->string('campaign_info_form_id')->nullable();
            $table->string('campaign_info_mcid')->nullable();
            $table->string('campaign_info_uuid')->nullable();
            $table->string('campaign_info_suid')->nullable();
            $table->string('campaign_info_rule_id')->nullable();
            $table->string('campaign_info_project_id')->nullable();
            $table->string('campaign_info_routing_request_id')->nullable();
            $table->string('campaign_info_forced_assignment')->nullable();
            $table->dateTime('created_at_sell_do')->nullable();
            $table->string('direction')->nullable();
            $table->string('duration')->nullable();
            $table->string('feedback')->nullable();
            $table->longText('message')->nullable();
            $table->longText('notes')->nullable();
            $table->string('offline')->nullable();
            $table->string('originator')->nullable();
            $table->string('recipient')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('remote_file')->nullable();
            $table->string('remote_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('updated_at_sell_do')->nullable();
            $table->string('initiated_by')->nullable();
            $table->string('lead_id')->nullable();
            $table->string('lead_first_name')->nullable();
            $table->string('lead_last_name')->nullable();
            $table->string('lead_email')->nullable();
            $table->string('lead_phone')->nullable();
            $table->string('event')->nullable();
            $table->longText('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_activities');
    }
};
