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

    /** @test */
    function test_visits_root() {
        $this->visit('/');
    }

   /** @test */
    function test_visits_task_view() {
        $this->visit('/task/1')->see("Hours Worked");
    }

    /** @test */
    function test_checks_for_invalid_start_time() {
        $this->visit('/task/1')
            ->type('11:99', '#startt-search')
            ->click('#taskType')
            ->See('pink');
    }

    /** @test */
    function test_checks_for_invalid_end_time() {
        $this->visit('/task/1')
            ->type('11:99', '#endt-search')
            ->click('#taskType')
            ->See('pink');
    }

    /** @test */
    function test_checks_empty_start_and_end_times() {
        $this->visit('/task/1')
            ->type('', '#startt-search')
            ->type('', '#endt-search')
            ->click('#taskType')
            ->notSee('pink');
    }

    /** @test */
    function test_saveButton_is_disabled_when_start_end_times_are_empty() {
        $this->visit('/task/1')
            ->type('', '#startt-search')
            ->type('', '#endt-search')->wait($this->delayMe)
            ->select('#taskType', 2)
            ->click('#saveButton')
            ->click('#refresh')
            ->notSee("Test");
    }

    /** @test */
    function test_checks_for_valid_start_time() {
       $this->visit('/task/1')
           ->type('18:00', '#startt-search')
           ->click('#taskType')
           ->notSee('pink');
    }

    /** @test */
    function test_checks_for_valid_end_time() {
        $this->visit('/task/1')
            ->type('18:00', '#endt-search')
            ->click('#taskType')
            ->notSee('pink');
    }

    /** @test */
    function test_checks_for_start_time_overlap() {
        $this->visit('/task/1')
            ->type('07:00', '#startt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_checks_for_end_time_overlap() {
        $this->visit('/task/1')
            ->type('11:00', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_start_time_is_after_end_time() {
        $this->visit('/task/1')
            ->type('20:00', '#startt-search')
            ->type('19:00', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_checks_for_start_and_end_time_overlap() {
        $this->visit('/task/1')
            ->type('07:00', '#startt-search')
            ->type('11:00', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_start_time_can_be_the_same_as_existing_end_time() {
        $this->visit('/task/1')
            ->type('11:30', '#startt-search')
            ->click('#taskType')
            ->notSee('pink');
    }

    /** @test */
    function test_start_time_can_fill_an_exiting_time_slot() {
        $this->visit('/task/1')
            ->type('11:30', '#startt-search')
            ->type('12:00', '#endt-search')
            ->click('#taskType')
            ->notSee('pink');
    }

    /** @test */
    function test_start_time_overlaps_existing_time_slot() {
        $this->visit('/task/1')
            ->type('11:29', '#startt-search')
            ->type('12:00', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_end_time_overlaps_existing_time_slot() {
        $this->visit('/task/1')
            ->type('11:30', '#startt-search')
            ->type('12:01', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_both_times_overlaps_existing_time_slot() {
        $this->visit('/task/1')
            ->type('11:29', '#startt-search')
            ->type('12:01', '#endt-search')
            ->click('#taskType')
            ->see('pink');
    }

    /** @test */
    function test_write_record() {
        $this->visit('/task/1')
            ->type('11:30', '#startt-search')
            ->type('12:00', '#endt-search')->wait($this->delayMe)
            ->select('#taskType', 2)
            ->click('#saveButton')
            ->see("Test");
    }

}