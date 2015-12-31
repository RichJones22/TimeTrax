<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;


class testTaskView extends Selenium
{
    use Laravel;

    function testDeleteTaskTypeTableData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('delete_taskType_data');

        $newTestClass->tearDown();

        return $this;

    }

    public function createData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('add_taskType_data');

        $newTestClass->tearDown();

        return $this;
    }

    function testCreatingDataThatAlreadyExists()
    {
        $this->testDeleteTaskTypeTableData();

        $this->visit('/taskType/show/1')->createData()
            ->type('Lunch', '#taskType01')
            ->type('Lunch break','description')
            ->tick('#taskType01')
            ->click('saveButtonTaskType')->wait(15000)
            ->see('Task Type Maintenance');
    }


}