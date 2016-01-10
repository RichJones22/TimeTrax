<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Project extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create project table
         */
        Schema::create('project', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('flag_recording_time_for');
            $table->timestamps();
        });

        /**
         * create foreign key to the client table
         */
        Schema::table('project', function(Blueprint $table) {
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
        Schema::drop('project');
    }
}
