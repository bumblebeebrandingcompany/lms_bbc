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
        Schema::create('lead_events', function (Blueprint $table) {
            $table->id();
            
            $table->string('sell_do_lead_id')
                ->nullable();
                
            $table->string('lead_id')
                ->nullable();

            $table->string('event_type')
                ->nullable();

            $table->longText('webhook_data')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_events');
    }
};
