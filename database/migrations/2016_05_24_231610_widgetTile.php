<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetTile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_tile', function(Blueprint $table){
            $table->increments('id');
            $table->integer('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        /**
         * create foreign key to the widget_ribbon_tile table
         */
        Schema::table('widget_tile', function(Blueprint $table) {
            $table->integer('tile_id')->unsigned();
            $table->foreign('tile_id')->references('id')->on('widget_ribbon_tile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_tile');
    }
}
