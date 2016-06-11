<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_type', function(Blueprint $table){
            $table->increments('id');
            $table->integer('widget_name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        
        Schema::table('widget_legacy', function(Blueprint $table) {
            $table->integer('widget_type_id')->unsigned();
            $table->foreign('widget_type_id')->references('id')->on('widget_type');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_type');
    }
}
