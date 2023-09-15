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
        Schema::create('note_activities', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->nullable();
            $table->string('lead_first_name')->nullable();
            $table->string('lead_last_name')->nullable();
            $table->string('lead_phone')->nullable();
            $table->string('lead_email')->nullable();
            $table->string('event')->nullable();
            $table->string('payload_id')->nullable();
            $table->longText('payload_content')->nullable();
            $table->dateTime('payload_created_at')->nullable();
            $table->string('payload_note_type')->nullable();
            $table->dateTime('payload_updated_at')->nullable();
            $table->longText('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_activities');
    }
};
