<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `clients` CHANGE `email` `email` varchar(255) DEFAULT NULL");
        DB::statement("ALTER TABLE `agencies` CHANGE `email` `email` varchar(255) DEFAULT NULL");
        DB::statement("ALTER TABLE `leads` CHANGE `source_id` `source_id`  BIGINT DEFAULT NULL");


        Schema::table('leads', function (Blueprint $table) {
            $table->string('name')
                ->nullable()
                ->after('id');

            $table->longText('comments')
                ->nullable()
                ->comment('Customer comments')
                ->after('lead_details');
            
            $table->longText('cp_comments')
                ->nullable()
                ->comment('channel partner comments')
                ->after('comments');
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
