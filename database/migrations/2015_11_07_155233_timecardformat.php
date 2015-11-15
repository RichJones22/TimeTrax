<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timecardformat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create time_card table
         */
        Schema::create('time_card_format', function(Blueprint $table){
            $table->increments('id');
            $table->string('description')->unique();
            $table->char('dow_00',10);
            $table->char('dow_01',10);
            $table->char('dow_02',10);
            $table->char('dow_03',10);
            $table->char('dow_04',10);
            $table->char('dow_05',10);
            $table->char('dow_06',10);
            $table->timestamps();
        });

        /**
         * create foreign key to the client table
         */
        Schema::table('time_card_format', function(Blueprint $table) {
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('client');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('time_card_format');
    }
}
