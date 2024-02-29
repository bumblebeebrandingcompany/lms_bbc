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
        Schema::create('notes_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('note_list_id');
            $table->string('note_text');
            $table->bigInteger('stage_id');
            $table->bigInteger('tag_id');
            $table->bigInteger('lead_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes_list');
    }
};
