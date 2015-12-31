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

    /** @test */
    function test_visits_root() {
        $this->visit('/');
    }

    /** @test */
    public function testCreateData()
    {
        $newTestClass = new testTaskView();

        $newTestClass->visit('create_data');

        $newTestClass->tearDown();

        return $this;

    }

   /** @test */
    function test_visits_taskType_view() {
        $this->visit('/taskType/show/1')->see("Type");
    }

    /** @test */
    function test_checks_for_valid_type_one_word() {
        $this->visit('/taskType/show/1')
            ->type('Lunch', '#taskType01')
            ->type('Lunch break','description')
            ->notSee('Error: Type restricted to one word.');
    }

    /** @test */
    function test_checks_for_invalid_type_two_words() {
        $this->visit('/taskType/show/1')
            ->type('coding tasks', '#taskType01')
            ->type('description','description')
            ->See('Error: Type restricted to one word.');
    }

    /** @test */
    function test_checks_for_invalid_word_cant_exist() {
        $this->visit('/taskType/show/1')
            ->type('Code', '#taskType01')
            ->type('description','description')
            ->See('Error: Type already exists.');
    }


    /** @test */
    function test_checks_for_successful_insert() {
        $this->visit('/taskType/show/1')
            ->type('Lunch', '#taskType01')
            ->type('Lunch break','description')
            ->tick('#taskType01')
            ->click('saveButtonTaskType')
            ->see('Lunch');
    }

    /** @test */
    function test_checks_for_successful_delete() {
        $this->visit('/taskType/show/1')
            ->click('Lunch')
            ->notSee('Lunch');
    }

    /** @test */
    function test_checks_for_unsuccessful_delete() {
        $this->visit('/taskType/show/1')
            ->click('Code')
            ->see("Type (Code) currently exists on tasks.");
    }

}