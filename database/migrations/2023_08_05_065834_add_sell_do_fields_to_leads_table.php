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

        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('sell_do_is_exist')->default(false)->after('webhook_response')->comment('first response from sell do');
            $table->text('sell_do_lead_created_at')->nullable()->after('webhook_response');
            $table->string('sell_do_lead_id')->nullable()->after('webhook_response');
            $table->text('sell_do_response')->nullable()->after('webhook_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
