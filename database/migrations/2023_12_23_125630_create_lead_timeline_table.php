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
        $table->bigInteger('payload');
        $table->text('description');
        $table->timestamps();

    });
}
    public function down()
    {
        Schema::dropIfExists('lead_timeline');
    }
}

