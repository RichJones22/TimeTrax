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

    /** @test */
    function test_checks_for_unsuccessful_delete()
    {

        $this->setRDBMSTrue($this->getClassName($this));

        $this->visit('/taskType/1')
            ->click('Code')
            ->see("Integrity constraint violation: 1451");

        $this->setRDBMSFalse($this->getClassName($this));
    }

    function createTaskTypeData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('add_taskType_data');

        $newTestClass->tearDown();

        return $this;
    }

    function testCreatingDataThatAlreadyExists()
    {
        $this->setRDBMSTrue($this->getClassName($this));

        $this->visit('/taskType/1')->createTaskTypeData()
            ->type('Lunch', '#taskType')
            ->type('Lunch break', '#description')
            ->tick('#taskType')
            ->click('saveButtonTaskType')->wait(5000)
            ->see('Integrity constraint violation: 1062');

        $this->setRDBMSFalse($this->getClassName($this));
    }
}
