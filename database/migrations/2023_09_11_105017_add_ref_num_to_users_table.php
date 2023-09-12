<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Utils\Util;
use App\Models\User;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ref_num')
                ->after('id')
                ->nullable();
        });

        $users = User::all();
        if(count($users) > 0) {
            $util = new Util();
            foreach ($users as $user) {
                $ref_num = $util->generateUserRefNum($user);
                $user->ref_num = $ref_num;
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
