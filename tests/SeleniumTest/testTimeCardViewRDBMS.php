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

/**
 * Note:  For this test to work the 'static protected $testRDBMS' needs to be set to true
 *
 * Class testTimeCardView
 */

class testTimeCardView extends Selenium
{
    use Laravel;

    protected $baseUrl = 'http://timetrax.dev';

    function deleteData() {
        $newTestClass = new testTimeCardView();

        $newTestClass->visit("/delete_data");

        $newTestClass->tearDown();

        return $this;
    }

    function createData() {
        $newTestClass = new testTimeCardView();

        $newTestClass->visit("/create_data");

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

    public function setTestingRDBMSTrue()
    {
        DB::
    }

    function testDuplicateTimeCardIntegrityConstraintViolationNotCaught()
    {

        $this->deleteData();
        $this->createData()->wait();

        $this->visit("/timeCard/2015-11-12")
            ->see("( 2015-11-08 - 2015-11-14 )")
            ->waitClosure()
            ->type('8', '#dow_01')
            ->tick('#dow_01')
            ->select('#workType', 2)
            ->click('#saveButtonTimeCard')
            ->see("2300");

    }

}