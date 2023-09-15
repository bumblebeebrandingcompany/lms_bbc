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
        Schema::create('new_leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('primary_email')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->dateTime('received_on')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('stage')->nullable();
            $table->string('status')->nullable();
            $table->string('hotness')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('team_name')->nullable();
            $table->text('interested_properties')->nullable();
            $table->string('generated_from')->nullable();
            $table->string('campaign_source')->nullable();
            $table->string('campaign_mt')->nullable();
            $table->string('campaign_mv')->nullable();
            $table->string('requirement_purpose')->nullable();
            $table->longText('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_leads');
    }
};
