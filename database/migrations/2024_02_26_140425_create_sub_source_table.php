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
        Schema::create('sub_source', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('project_id'); // Add the new lead_id column
            $table->bigInteger('campaign_id'); // Add the new lead_id column
            $table->bigInteger('source_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsource');
    }
};
