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
        Schema::table('sources', function (Blueprint $table) {
            $table->string('email_key')
                ->after('outgoing_apis')
                ->comment('email key to get data from incoming webhook')
                ->nullable();

            $table->string('phone_key')
                ->after('email_key')
                ->comment('phone key to get data from incoming webhook')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
