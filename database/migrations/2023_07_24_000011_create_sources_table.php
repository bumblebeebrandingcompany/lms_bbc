<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');

            $table->string('webhook_secret')
                ->comment('incoming webhook secret key');

            $table->longText('outgoing_webhook')
                ->comment('outgoing webhook details')
                ->nullable();
            
            $table->longText('outgoing_apis')
                ->comment('outgoing api details')
                ->nullable();

            $table->timestamps();
        });
    }
}
