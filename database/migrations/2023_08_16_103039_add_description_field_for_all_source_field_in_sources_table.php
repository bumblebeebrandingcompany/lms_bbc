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
            $table->boolean('is_cp_source')
                ->after('name')
                ->comment('to be used when cp is adding lead')
                ->default(0);
                
            $table->text('source_field1_description')
                ->after('source_field1')
                ->comment('description for source_field1')
                ->nullable();

            $table->text('source_field2_description')
                ->after('source_field2')
                ->comment('description for source_field2')
                ->nullable();

            $table->text('source_field3_description')
                ->after('source_field3')
                ->comment('description for source_field3')
                ->nullable();

            $table->text('source_field4_description')
                ->after('source_field4')
                ->comment('description for source_field4')
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
