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

class testTaskView extends Selenium
{
    use Laravel;

    function testDeleteTaskTableData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('delete_task_data');

        $newTestClass->tearDown();

        return $this;

    }

    public function createDate()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('add_task_data_firstPass');

        $newTestClass->tearDown();

        return $this;
    }

    function testDataCreatedAfterViewWasDisplayedInsideRange()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->createDate()
            ->type('13:00', '#startt-search')->wait(1000)
            ->type('14:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')
            ->notSee(appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));
    }

    function testDataCreatedAfterViewWasDisplayedOverLapStartTime()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->createDate()
            ->type('11:30', '#startt-search')->wait(1000)
            ->type('14:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')
            ->notSee(appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));
    }

    function testDataCreatedAfterViewWasDisplayedOverLapEndTime()
    {
        $this->testDeleteTaskTableData();

        $this->visit('/task/1')->createDate()
            ->type('16:00', '#startt-search')->wait(1000)
            ->type('18:00', '#endt')
            ->select('#taskType', 1)
            ->type('rich was here','#notes')
            ->click('#saveButton')
            ->notSee(appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));
    }

}