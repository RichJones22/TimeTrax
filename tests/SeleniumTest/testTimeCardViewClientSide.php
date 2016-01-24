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
    function test_reset_data() {
        $this->visit("/create_data");
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

    /** @test */
    function test_visits_timeCard_hours_for_day_exist() {
        $this->visit("/timeCard/2015-11-12")->wait(1000)
            ->select('#workType', 2)
            ->type('8', '#dow_04')
            ->tick('#dow_05')
            ->click('#saveButtonTimeCard')
            ->see('pink');
    }

    /** @test */
    function test_visits_delete_data_for_timeCard_toggle_type_between_two_days() {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")
            ->click('#deleteButton2')
            ->notSee('deleteButton2');
    }

    /** @test */
    function test_visits_add_data_for_timeCard_toggle_type_between_two_days() {
        $this->visit("/timeCard/2015-11-12")->see("( 2015-11-08 - 2015-11-14 )")
            ->type('8', '#dow_04')
            ->tick('#dow_05')
            ->select('#workType', 2)
            ->click('#saveButtonTimeCard')
            ->see('Defect--The catalog view is performing too slowly.');
    }

    /** @test */
    function test_visits_timeCard_toggle_type_between_two_days() {
        $this->visit("/timeCard/2015-11-12")->wait(1000)
            ->select('#workType', 2)
            ->type('8', '#dow_04')
            ->type('8', '#dow_05')
            ->tick('#dow_06')
            ->select('#workType', 1)->wait(2000)
            ->select('#workType', 2)->wait(2000)
            ->select('#workType', 3)->wait(2000)
            ->select('#workType', 2)->wait(2000)
            ->click('#saveButtonTimeCard')->wait(2000)
            ->see('pink');
    }

}