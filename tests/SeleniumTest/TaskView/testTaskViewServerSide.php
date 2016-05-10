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


class testTaskView extends Selenium
{
    use Laravel, DataReset;

    private $delayMe = 2000;


    function testDeleteTaskTableData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('delete_task_data');

        $newTestClass->tearDown();

        return $this;

    }

    public function waitClosure()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('add_task_data_firstPass');

        $newTestClass->tearDown();

        return $this;
    }

    function testDataCreatedAfterViewWasDisplayedInsideRange()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->waitClosure()
            ->type('13:00', '#startt-search')
            ->type('14:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')->wait(2000)
            ->see('One of your entered time values overlaps with existing data.  Your data has been refreshed.');
    }

    function testDataCreatedAfterViewWasDisplayedOverLapStartTime()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->waitClosure()
            ->type('11:30', '#startt-search')
            ->type('14:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')->wait(2000)
            ->see('One of your entered time values overlaps with existing data.  Your data has been refreshed.');
    }

    function testDataCreatedAfterViewWasDisplayedOverLapEndTime()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->waitClosure()
            ->type('16:00', '#startt-search')
            ->type('18:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')->wait(2000)
            ->see('One of your entered time values overlaps with existing data.  Your data has been refreshed.');
    }

    // javascript test.  the error message is replace with the date in the top part of the table.
    function testDataCreatedAfterViewWasDisplayedOverLapEndTimeBannerChange()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->waitClosure()
            ->type('16:00', '#startt-search')
            ->type('18:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')->wait(15000)
            ->see('Nov 12, 2015');
    }

}