<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_map', function(Blueprint $table){
            $table->increments('id');
            $table->integer('widget_class_name')->unique();
            $table->string('widget_type', 20)->default('map');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        /**
         * create foreign key to the widget table
         */
        Schema::table('widget_map', function(Blueprint $table) {
            $table->integer('widget_id')->unsigned();
            $table->foreign('widget_id')->references('id')->on('widget_dashboard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_map');
    }
}
