<?php

// database/migrations/yyyy_mm_dd_add_tag_id_to_parent_stages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTagIdToParentStagesTable extends Migration
{
    public function up()
    {
        Schema::table('parent_stages', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('parent_stages', function (Blueprint $table) {
            // $table->dropForeign(['tag_id']);
            // $table->dropColumn('tag_id');
        });
    }
}
