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
            $table->integer('time_card_hours_worked_id')->unsigned()->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->float('hours_worked', 8,4);
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->unique(['time_card_hours_worked_id', 'start_time']);
        });

        /**
         * create foreign key to the task table
         */
        Schema::table('task', function (Blueprint $table) {
            $table->foreign('time_card_hours_worked_id')->references('id')->on('time_card_hours_worked')->nullable();
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
