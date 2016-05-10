<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;

use \App\Traits\Tests\DataReset;


class testTimeCardView extends Selenium
{
    use Laravel, DataReset;

    private $delayMe = 15000;

    /**
     * these tests are run as a unit, so we begin by resetting the data.
     * @test
     */
    function test_reset_data() {

        $this->deleteData($this->getClassName($this));
        $this->createData($this->getClassName($this));

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

        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")->waitClosure()
            ->type('8', '#dow_01')
            ->tick('#dow_01')
            ->select('#workType', 2)
            ->click('#saveButtonTimeCard')->wait($this->delayMe)
            ->see("One of your entered time values overlaps with existing data.  Your data has been refreshed.")
            ->see("( 2015-11-08 - 2015-11-14 )");
    }

}