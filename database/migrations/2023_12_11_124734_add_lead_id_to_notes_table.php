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
        Schema::table('notes', function (Blueprint $table) {
            $table->bigInteger('lead_id')->unsigned(); // Add the new lead_id column
            $table->foreign('lead_id')->references('id')->on('leads'); // Create a foreign key constraint
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('lead_id');
        });
    }
};
