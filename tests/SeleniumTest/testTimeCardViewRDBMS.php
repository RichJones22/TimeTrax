<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;
use \App\Helpers\appGlobals;

class testTimeCardView extends Selenium
{
    use Laravel;

    function testDeleteTaskTableData()
    {
        $newTestClass = new testTimeCardView();

        // note:  currently this test requires that you 'artisan migrate:refresh' the db
        $newTestClass->visit('create_data');

        $newTestClass->tearDown();

        return $this;

    }

    public function waitClosure()
    {
        $newTestClass = new testTimeCardView();

        $newTestClass->visit('add_timeCard_data');

        $newTestClass->tearDown();

        return $this;
    }

    function testDuplicateTimeCardIntegrityConstraintViolationNotCaught()
    {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")->waitClosure()
            ->type('8', '#dow_01')
            ->tick('#dow_01')
            ->select('#workType', 3)
            ->click('#saveButtonTimeCard')
            ->see("2300");
    }

}