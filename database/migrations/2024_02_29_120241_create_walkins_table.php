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
        Schema::create('walkins', function (Blueprint $table) {
            $table->id();
            $table->string('ref_num');
            $table->string('father_name');
            $table->string('father_occupation');
            $table->string('father_income');
            $table->string('mother_name');
            $table->string('mother_occupation');
            $table->string('mother_income');
            $table->string('gaurdian_name');
            $table->string('gaurdian_occupation');
            $table->string('gaurdian_income');
            $table->string('child_name');
            $table->string('child_age');
            $table->string('child_gender');
            $table->date('dob');
            $table->string('email');
            $table->string('secondary_email');
            $table->string('phone');
            $table->string('secondary_phone');
            $table->string('address');
            $table->string('previous_school');
            $table->string('intake_year');
            $table->string('board');
            $table->string('previous_school_location');
            $table->biginteger('sub_source_id');
            $table->bigInteger('added_by');
            $table->string('comments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkins');
    }
};
