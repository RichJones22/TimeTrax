<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestingSeleniumVariables extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'testing_selenium_variables';

    /**
     * fillable fields
     */
    protected $fillable = [
        'testingRDBMS'
    ];
}
