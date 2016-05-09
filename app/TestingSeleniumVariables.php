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

    public static function setRDBMSTrue()
    {
        $tmp = new TestingSeleniumVariables();

        $tmp->where('id', '1')->truncate();

        dd($tmp);

        TestingSeleniumVariables::where('id', '1')->update(['testingRDBMS' => true]);

        dd("im here");

//        return TestingSeleniumVariables::where('id', '1')->select('testingRDBMS')->first();

    }
}
