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

    protected $baseUrl = 'http://timetrax.dev';

    private $delayMe = 15000;

    function deleteData()
    {
        $newTestClass = new testTimeCardView();

        $newTestClass->visit("/delete_data");

        $newTestClass->tearDown();

        return $this;

    }

    function createData()
    {
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

    function testDuplicateTimeCardIntegrityConstraintViolationCaught()
    {
        $this->deleteData();
        $this->createData();

        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")->waitClosure()
            ->type('8', '#dow_01')
            ->tick('#dow_01')
            ->select('#workType', 2)
            ->click('#saveButtonTimeCard')->wait($this->delayMe)
            ->see("One of your entered time values overlaps with existing data.  Your data has been refreshed.")
            ->see("( 2015-11-08 - 2015-11-14 )");
    }

}