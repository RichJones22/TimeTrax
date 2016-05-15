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
    function test_reset_data() {

        $this->deleteData($this->getClassName($this));
        $this->createData($this->getClassName($this));

        return $this;
    }

    public function createTaskTypeData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('add_taskType_data');

        $newTestClass->tearDown();

        return $this;
    }

    function testCreatingDataThatAlreadyExists()
    {
        $this->visit('/taskType/1')->wait($this->delayMe)
            ->createTaskTypeData()
            ->type('Lunch', '#taskType')
            ->type('Lunch break','#description')
            ->tick('#taskType')
            ->click('saveButtonTaskType')->wait(15000)
            ->see('Task Type Maintenance');
    }


}