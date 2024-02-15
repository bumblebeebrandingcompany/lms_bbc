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
        Schema::create('application_purchased', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id');
            $table->bigInteger('user_id');
            $table->bigInteger('application_no')->nullable();
            $table->date('follow_up_date');
            $table->time('follow_up_time');
            $table->string('notes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_purchased');
    }
};
