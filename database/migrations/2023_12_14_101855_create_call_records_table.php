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
        Schema::create('call_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');

            $table->string('called_on');
            $table->string('client_number');
            $table->unsignedBigInteger('call_duration');
            $table->time('call_on_time');
            $table->string('call_recordings');
            $table->string('status');
            $table->string('call_id');
            $table->string('direction');
            $table->string('description');
            $table->string('did number');
            $table->json('call_flow');
            $table->json('hangup_cause');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_records');
    }
};
