<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create work_type table
         */
        Schema::create('work_type', function(Blueprint $table){
            $table->increments('id');
            $table->string('type')->unique();
            $table->timestamps();
        });


        /**
         * create foreign key to the work_type table
         */
        Schema::table('work', function(Blueprint $table) {
            $table->integer('work_type_id')->unsigned();
            $table->foreign('work_type_id')->references('id')->on('work_type');
        });

        /**
         * create foreign key to the client table
         */
        Schema::table('work_type', function(Blueprint $table) {
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
        Schema::table('work', function(Blueprint $table) {
            $table->dropForeign('work_work_type_id_foreign');
        });

        Schema::drop('work_type');
    }
}
