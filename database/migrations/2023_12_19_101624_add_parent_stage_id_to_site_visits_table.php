<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('site_visits', function (Blueprint $table) {
            $table->unsignedBigInteger('stage_id')->nullable();
            $table->foreign('stage_id', 'stage_fk_8745955')->references('id')->on('parent_stages');
        });
    }
    public function down(): void
    {
        Schema::table('site_visits', function (Blueprint $table) {
            //
        });
    }
};
