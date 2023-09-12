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
            $table->string('ref_prefix')
                ->after('webhook_fields')
                ->default('REF')
                ->comment('prefix for ref num');

            $table->string('ref_count')
                ->after('ref_prefix')
                ->default(0)
                ->comment('total count of ref generated');
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
