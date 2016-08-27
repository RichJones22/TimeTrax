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

/**
 * Note:  For this test to work the 'static protected $testRDBMS' needs to be set to true
 *
 * Class testTimeCardView
 */

class testTimeCardView extends Selenium
{
    use Laravel, DataReset;


    /**
     * these tests are run as a unit, so we begin by resetting the data.
     * @test
     */
    function test_reset_data()
    {

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



    function testDuplicateTimeCardIntegrityConstraintViolationNotCaught()
    {

        $this->setRDBMSTrue($this->getClassName($this));

        $this->visit("/timeCard/2015-11-12")
            ->see("( 2015-11-08 - 2015-11-14 )")
            ->waitClosure()
            ->type('7', '#dow_01')
            ->tick('#dow_01')
            ->select('#workType', 2)
            ->click('#saveButtonTimeCard')
            ->see("2300");

        $this->setRDBMSFalse($this->getClassName($this));
    }
}
