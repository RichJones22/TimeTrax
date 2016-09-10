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
    function test_visits_root()
    {
        $this->visit('/');
    }

    /** @test */
    function it_checks_page_not_found()
    {
        $this->visit("/taskType/99/task/1")->see("Your Task Type ID does not exist.");
    }

   /** @test */
    function test_visits_taskType_view()
    {
        $this->visit('/taskType/1')->see("Type");
    }

    /** @test */
    function test_checks_for_valid_type_one_word()
    {
        $this->visit('/taskType/1')
            ->type('Lunch', '#taskType')
            ->type('Lunch break', 'description')
            ->notSee('Error: Type restricted to one word.');
    }

    /** @test */
    function test_checks_for_invalid_type_two_words()
    {
        $this->visit('/taskType/1')
            ->type('coding tasks', '#taskType')
            ->type('description', 'description')
            ->See('Error: Type restricted to one word.');
    }

    /** @test */
    function test_checks_for_invalid_word_cant_exist()
    {
        $this->visit('/taskType/1')
            ->type('Code', '#taskType')
            ->type('description', 'description')
            ->See('Error: Type already exists.');
    }

    /** @test */
    function test_checks_for_successful_insert()
    {
        $this->visit('/taskType/1')
            ->type('Lunch', '#taskType')
            ->type('Lunch break', 'description')
            ->tick('#taskType')
            ->click('saveButtonTaskType')
            ->see('Lunch');
    }

    /** @test */
    function test_checks_for_successful_delete()
    {
        $this->visit('/taskType/1')
            ->click('Lunch')
            ->notSee('Lunch');
    }

    /** @test */
    function test_checks_for_unsuccessful_delete()
    {
        $this->visit('/taskType/1')
            ->click('Code')
            ->see("Type (Code) currently exists on tasks.");
    }

    /** @test */
    function test_grid_updates()
    {

        $this->setTtvTypeClearTextTrue($this->getClassName($this));

        $this->visit('/taskType/1')
            ->click('#rowTaskTypeId_0')
            ->type("", '#rowTaskTypeId_0')
            ->click('#rowTaskTypeDesc_0')->wait($this->delayMe)
            ->see('Error: Type must contain a value.')
            ->click('#rowTaskTypeId_0')
            ->type("test", '#rowTaskTypeId_0')
            ->click('#rowTaskTypeDesc_0')->wait($this->delayMe)
            ->see('Error: Type already exists.')
            ->click('#rowTaskTypeId_0')
            ->type("this is it", '#rowTaskTypeId_0')
            ->click('#rowTaskTypeDesc_0')->wait($this->delayMe)
            ->see('Error: Type restricted to one word.')
            ->click('#taskTypeRefreshPage')
            ->click('#rowTaskTypeDesc_0')
            ->click('#rowTaskTypeDesc_1')->wait($this->delayMe)
            ->click('#taskTypeRefreshPage')
            ->click('#rowTaskTypeDesc_0')
            ->type(" stuff...",'#rowTaskTypeDesc_0')
            ->click('#rowTaskTypeDesc_1')
            ->click('#taskTypeRefreshPage')->wait($this->delayMe)
            ->see(' stuff...')
            ->click('#rowTaskTypeId_0')
            ->type("testing1",'#rowTaskTypeId_0')
            ->click('#rowTaskTypeId_1')
            ->click('#taskTypeRefreshPage')->wait($this->delayMe)
            ->see('testing1')
        ;

        $this->setTtvTypeClearTextFalse($this->getClassName($this));
    }
}
