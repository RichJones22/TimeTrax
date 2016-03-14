<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaskType extends Migration
{
    /**
     * abstract:
     *          why duplicate hours_worked on both the time_card and task tables. The idea being that you could just
     *          add hours to the time_card table, as you would in a classical time card; but, you could also add hours
     *          to the task table, if you wanted to break those hours down by task.  In order to do this, hours would
     *          need to be recorded separately, once on the time_card table as an aggregate for the day, and then again
     *          on the task table, as a breakdown by task.
     */

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
        Schema::create('task_type', function(Blueprint $table){
            $table->increments('id');
            $table->char('type', 15)->unique();
            $table->string('description');
            $table->timestamps();
        });

        /**
         * create foreign key to the task table
         */
        Schema::table('task', function(Blueprint $table) {
            $table->integer('task_type_id')->unsigned();
            $table->foreign('task_type_id')->references('id')->on('task_type');
        });

        /**
         * create foreign key to the client table
         */
        Schema::table('task_type', function(Blueprint $table) {
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
        Schema::table('task', function(Blueprint $table) {
            $table->dropForeign('task_task_type_id_foreign');
        });

        Schema::drop('task_type');
    }
}
