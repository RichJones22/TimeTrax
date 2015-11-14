<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Task extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create task table
         */
        Schema::create('task', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time')->unique();
            $table->time('end_time');
            $table->float('hours_worked');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        /**
         * create foreign key to the task table
         */
        Schema::table('task', function (Blueprint $table) {
            $table->integer('time_card_id')->unsigned()->nullable();
            $table->foreign('time_card_id')->references('id')->on('time_card')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task');
    }
}
