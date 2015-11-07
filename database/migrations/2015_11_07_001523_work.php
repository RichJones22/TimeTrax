<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Work extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * create work table
         */
        Schema::create('work', function(Blueprint $table){
            $table->increments('id');
            $table->string('work_type_description')->unique();
            $table->timestamps();
        });

        /**
         * create foreign key to the project table
         */
        Schema::table('work', function(Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('project');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work', function(Blueprint $table) {
            $table->dropForeign('work_project_id_foreign');
        });

        Schema::drop('work');

    }
}
