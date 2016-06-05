<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetRibbonTile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_ribbon_tile', function(Blueprint $table){
            $table->increments('id');
            $table->string('widget_name', 100);
            $table->string('widget_type', 20)->default('ribbonTile');
            $table->boolean('active')->default(true);
            $table->timestamps();
//            $table->unique(['widget_ribbon_id', 'widget_tile_id']);
        });

        /**
         * create foreign key to the widget table
         */
        Schema::table('widget_ribbon_tile', function(Blueprint $table) {
            $table->integer('widget_id')->unsigned();
            $table->foreign('widget_id')->references('id')->on('widget_dashboard')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_ribbon_tile');
    }
}
