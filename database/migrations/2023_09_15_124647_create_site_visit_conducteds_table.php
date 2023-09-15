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
        Schema::create('site_visit_conducteds', function (Blueprint $table) {
            $table->id();
            $table->string('id_selldo')->nullable();
            $table->text('agenda')->nullable();
            $table->string('campaign_info_name')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('campaign_medium_type')->nullable();
            $table->string('campaign_medium_value')->nullable();
            $table->string('campaign_source')->nullable();
            $table->string('campaign_sub_source')->nullable();
            $table->dateTime('campaign_when')->nullable();
            $table->string('project_id_selldo')->nullable();
            $table->dateTime('ends_on')->nullable();
            $table->string('status')->nullable();
            $table->string('initiated_by')->nullable();
            $table->string('product_name')->nullable();
            $table->dateTime('sv_conducted_on')->nullable();
            $table->string('sv_conducted_by')->nullable();
            $table->string('sv_conducted_by_id')->nullable();
            $table->string('lead_id')->nullable();
            $table->string('lead_first_name')->nullable();
            $table->string('lead_last_name')->nullable();
            $table->string('lead_email')->nullable();
            $table->string('lead_phone')->nullable();
            $table->text('sv_notes')->nullable();
            $table->longText('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visit_conducteds');
    }
};
