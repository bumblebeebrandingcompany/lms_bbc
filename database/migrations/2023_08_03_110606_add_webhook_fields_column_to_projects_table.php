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
        Schema::table('projects', function (Blueprint $table) {
            $table->longText('outgoing_apis')
                ->nullable()
                ->comment('outgoing webhook details')
                ->after('description');
            
            $table->longText('webhook_fields')
                ->nullable()
                ->comment('unique column from incoming webhook & to be send with outgoing api')
                ->after('outgoing_apis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
