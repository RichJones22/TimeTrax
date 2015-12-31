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

    public function testCreateData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('create_data');

        $newTestClass->tearDown();

        return $this;

    }

    /** @test */
    function test_checks_for_unsuccessful_delete() {
        $this->visit('/taskType/show/1')
            ->click('Code')
            ->see("Integrity constraint violation: 1451");
    }

    function testDeleteTaskTypeTableData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('delete_taskType_data');

        $newTestClass->tearDown();

        return $this;

    }

    function createData()
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
            ->click('saveButtonTaskType')
            ->see('Integrity constraint violation: 1062');
    }


}