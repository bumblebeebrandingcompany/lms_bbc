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
        Schema::table('walkinform', function (Blueprint $table) {
            $table->bigInteger('sub_source_id'); // Add the new lead_id column
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walkinform', function (Blueprint $table) {
            //
        });
    }
};