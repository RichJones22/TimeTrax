<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaskType extends Migration
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
        Schema::create('task_type', function(Blueprint $table){
            $table->increments('id');
            $table->string('type')->unique();
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
