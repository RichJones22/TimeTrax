<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetRibbon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_ribbon', function(Blueprint $table){
            $table->increments('id');
            $table->integer('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        /**
         * create foreign key to the widget table
         */
        Schema::table('widget_ribbon', function(Blueprint $table) {
            $table->integer('ribbon_id')->unsigned();
            $table->foreign('ribbon_id')->references('id')->on('widget_ribbon_tile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_ribbon');
    }
}
