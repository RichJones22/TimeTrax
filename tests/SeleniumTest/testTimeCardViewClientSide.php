<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;


class testTimeCardView extends Selenium
{
    use Laravel;

    /** @test */
    function test_visits_root() {
        $this->visit('/');
    }

   /** @test */
    function test_visits_timeCard_cant_add_sun_hours() {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")
            ->type('8', '#dow_00')
            ->click('#saveButtonTimeCard')
            ->see('disabled');
    }

    /** @test */
    function test_visits_timeCard_add_sun_hours() {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")
            ->type('8', '#dow_00')
            ->tick('#dow_01')
            ->select('#workType', 3)
            ->click('#saveButtonTimeCard')
            ->see('Feature--A new landing page is required to support Fall 2016 GNO.');
    }

    /** @test */
    function test_visits_timeCard_delete_sun_hours() {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")
            ->click('#deleteButton3')
            ->notSee('deleteButton3');
    }

//    /** @test */
//    function test_visits_timeCard_hours_for_day_exist() {
//        $this->visit("/timeCard/2015-11-12")->wait(1000)
//            ->select('#workType', 3)
//            ->type('8', '#dow_04')
//            ->tick('#dow_05')
//            ->select('#workType', 2)->wait(1000)
//            ->tick('#dow_05')->wait(1000)
//            ->click('#saveButtonTimeCard')->wait()
//            ->see('pink');
//    }

//    /** @test */
//    function test_checks_for_save_button_disabled() {
//        $this->visit('/task/1')
//            ->type('11:99', '#startt-search')
//            ->click('#taskType')
//            ->See('pink');
//    }

//    /** @test */
//    function test_checks_for_invalid_end_time() {
//        $this->visit('/task/1')
//            ->type('11:99', '#endt-search')
//            ->click('#taskType')
//            ->See('pink');
//    }
//
//    /** @test */
//    function test_checks_empty_start_and_end_times() {
//        $this->visit('/task/1')
//            ->type('', '#startt-search')
//            ->type('', '#endt-search')
//            ->click('#taskType')
//            ->notSee('pink');
//    }
//
//    /** @test */
//    function test_checks_for_valid_start_time() {
//       $this->visit('/task/1')
//           ->type('18:00', '#startt-search')
//           ->click('#taskType')
//           ->notSee('pink');
//    }
//
//    /** @test */
//    function test_checks_for_valid_end_time() {
//        $this->visit('/task/1')
//            ->type('18:00', '#endt-search')
//            ->click('#taskType')
//            ->notSee('pink');
//    }
//
//    /** @test */
//    function test_checks_for_start_time_overlap() {
//        $this->visit('/task/1')
//            ->type('07:00', '#startt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_checks_for_end_time_overlap() {
//        $this->visit('/task/1')
//            ->type('11:00', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_start_time_is_after_end_time() {
//        $this->visit('/task/1')
//            ->type('20:00', '#startt-search')
//            ->type('19:00', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_checks_for_start_and_end_time_overlap() {
//        $this->visit('/task/1')
//            ->type('07:00', '#startt-search')
//            ->type('11:00', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_start_time_can_be_the_same_as_existing_end_time() {
//        $this->visit('/task/1')
//            ->type('11:30', '#startt-search')
//            ->click('#taskType')
//            ->notSee('pink');
//    }
//
//    /** @test */
//    function test_start_time_can_fill_an_exiting_time_slot() {
//        $this->visit('/task/1')
//            ->type('11:30', '#startt-search')
//            ->type('12:00', '#endt-search')
//            ->click('#taskType')
//            ->notSee('pink');
//    }
//
//    /** @test */
//    function test_start_time_overlaps_existing_time_slot() {
//        $this->visit('/task/1')
//            ->type('11:29', '#startt-search')
//            ->type('12:00', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_end_time_overlaps_existing_time_slot() {
//        $this->visit('/task/1')
//            ->type('11:30', '#startt-search')
//            ->type('12:01', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_both_times_overlaps_existing_time_slot() {
//        $this->visit('/task/1')
//            ->type('11:29', '#startt-search')
//            ->type('12:01', '#endt-search')
//            ->click('#taskType')
//            ->see('pink');
//    }
//
//    /** @test */
//    function test_write_record() {
//        $this->visit('/task/1')
//            ->type('11:30', '#startt-search')
//            ->type('12:00', '#endt-search')
//            ->select('#taskType', 2)
//            ->click('#saveButton')->wait(5000)
//            ->see("Test");
//    }

}