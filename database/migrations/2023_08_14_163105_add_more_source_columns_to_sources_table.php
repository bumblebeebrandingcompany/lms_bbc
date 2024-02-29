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
            $table->string('source_field1')
                ->after('name')
                ->comment('to be used for predefined_source_field1 in outgoing webhook')
                ->nullable();

            $table->string('source_field2')
                ->after('source_field1')
                ->comment('to be used for predefined_source_field2 in outgoing webhook')
                ->nullable();

            $table->string('source_field3')
                ->after('source_field2')
                ->comment('to be used for predefined_source_field3 in outgoing webhook')
                ->nullable();

            $table->string('source_field4')
                ->after('source_field3')
                ->comment('to be used for predefined_source_field4 in outgoing webhook')
                ->nullable();


            $table->string('additional_email_key')
                ->after('email_key')
                ->comment('additional email key to get data from incoming webhook')
                ->nullable();

            $table->string('secondary_phone_key')
                ->after('phone_key')
                ->comment('secondary phone key to get data from incoming webhook')
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
