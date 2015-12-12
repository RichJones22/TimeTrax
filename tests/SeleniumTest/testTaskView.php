<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;


class testTaskView extends Selenium
{


//    /** @test */
    function test_visits_root() {
        $this->visit('/');
    }
//
//    /** @test */
    function test_visits_task_view() {
        $this->visit('/task/show/1')->see("Hours Worked");
    }

//    /** @test */
    function test_checks_for_invalid_start_time() {
        $this->visit('/task/show/1')
            ->type('11:99', '#startt-search')->select('#endt',true)
            ->See('pink');

    }

    /** @test */
    function test_checks_for_valid_start_time() {
       $this->visit('/task/show/1')
           ->type('18:00', '#startt-search')->select('#endt',true)->wait(5000)
           ->notSee('pink');


    }
}