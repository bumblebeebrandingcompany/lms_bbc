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
        Schema::create('site_visit_scheduleds', function (Blueprint $table) {
            $table->id();
            $table->string('id_selldo')->nullable();
            $table->dateTime('acted_on')->nullable();
            $table->text('agenda')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('created_at_sell_do')->nullable();
            $table->string('followup_type')->nullable();
            $table->dateTime('scheduled_on')->nullable();
            $table->string('status')->nullable();
            $table->string('subject')->nullable();
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
        Schema::dropIfExists('site_visit_scheduleds');
    }
};
