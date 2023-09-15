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
        Schema::create('followup_scheduleds', function (Blueprint $table) {
            $table->id();
            $table->string('payload_id')->nullable();
            $table->dateTime('payload_acted_on')->nullable();
            $table->text('payload_agenda')->nullable();
            $table->text('payload_cancellation_reason')->nullable();
            $table->dateTime('payload_created_at')->nullable();
            $table->string('payload_followup_type')->nullable();
            $table->dateTime('payload_scheduled_on')->nullable();
            $table->string('payload_status')->nullable();
            $table->text('payload_subject')->nullable();
            $table->string('payload_initiated_by')->nullable();
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
        Schema::dropIfExists('followup_scheduleds');
    }
};
