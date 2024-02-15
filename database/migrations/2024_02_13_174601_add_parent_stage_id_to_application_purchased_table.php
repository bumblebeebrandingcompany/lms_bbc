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
        Schema::table('application_purchased', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_stage_id')->nullable();
            $table->foreign('parent_stage_id', 'stage_fk_8745955')->references('id')->on('parent_stages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_purchased', function (Blueprint $table) {
            //
        });
    }
};
