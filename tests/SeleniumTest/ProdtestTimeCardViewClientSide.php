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
    function test_visits_root()
    {
        $this->visit('http:/timetrax.premisesoftware.com/timeCard/2015-11-01');
    }

    /** @test */
    function test_visits_timeCard_add_sun_hours()
    {
        $this->visit("http:/timetrax.premisesoftware.com/timeCard/2015-11-01")->wait(1000)
            ->type('8', '#dow_00')
            ->tick('#dow_01')
            ->select('#workType', 3)
            ->click('#saveButtonTimeCard')->wait(1000)
            ->see('Feature--A new landing page is required to support Fall 2016 GNO.');
    }

    /** @test */
    function test_visits_delete_data_for_timeCard_toggle_type_between_two_days()
    {
        $this->visit("http:/timetrax.premisesoftware.com/timeCard/2015-11-01")->wait(1000)
            ->click('#deleteButton3')
            ->notSee('deleteButton3')->wait(1000);
    }
}
