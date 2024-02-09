<?php

// database/migrations/xxxx_xx_xx_create_lead_timeline_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTimelineTable extends Migration
{
    public function up()
{
    Schema::create('lead_timeline', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('lead_id');
        $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        $table->string('activity_type');
        $table->unsignedBigInteger('note_id')->nullable();
        $table->unsignedBigInteger('follow_up_id')->nullable();
        $table->unsignedBigInteger('site_visit_id')->nullable();
        $table->text('description');
        $table->timestamps();
        // Foreign keys for note_id, followup_id, and sitevisit_id
        $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
        $table->foreign('follow_up_id')->references('id')->on('follow_ups')->onDelete('cascade');
        $table->foreign('site_visit_id')->references('id')->on('site_visits')->onDelete('cascade');
    });
}
    public function down()
    {
        Schema::dropIfExists('lead_timeline');
    }
}

