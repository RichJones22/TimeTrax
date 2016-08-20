<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 5/9/16
 * Time: 7:19 PM
 */

namespace app\Traits\Tests;

trait DataReset
{

    protected $baseUrl = 'http://timetrax.dev';

    private function getClassName($thisCaller)
    {
        return get_class($thisCaller);
    }


    public function deleteData($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("/delete_data");

        $newTestClass->tearDown();

        return $this;
    }

    public function createData($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("/create_data");

        $newTestClass->tearDown();

        return $this;
    }

    function setRDBMSTrue($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("set_rdbms_true");

        $newTestClass->tearDown();

        return $this;
    }

    function setRDBMSFalse($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("set_rdbms_false");

        $newTestClass->tearDown();

        return $this;
    }

    function setTtvTypeClearTextTrue($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("set_ttvTypeClearText_true");

        $newTestClass->tearDown();

        return $this;
    }

    function setTtvTypeClearTextFalse($className)
    {
        $newTestClass = new $className();

        $newTestClass->visit("set_ttvTypeClearText_false");

        $newTestClass->tearDown();

        return $this;
    }
}
