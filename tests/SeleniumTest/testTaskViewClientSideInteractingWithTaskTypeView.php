<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 12/11/15
 * Time: 6:06 PM
 */

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;


class testTaskViewInteractions extends Selenium
{
    use Laravel;

    /** @test */
    function test_visits_root() {
        $this->visit('/');
    }

    /**
     * below tests test the routing from Task View to Task Type View and back.
     */

   /** @test */
    function test_visits_task_view() {
        $this->visit('/task/show/1')->see("Nov 12, 2015");
    }

    /** @test */
    function test_route_taskType_view() {
        $this->visit('/task/show/1')
            ->click('#routeToTaskTypeView');
    }

    /** @test */
    function test_route_taskType_view_refresh_the_page_route_to_task_view() {
        $this->visit('/task/show/1')
            ->click('#routeToTaskTypeView')
            ->see('Task Type Maintenance')
            ->click('#taskTypeRefreshPage')
            ->click('#routeToTaskView')
            ->see('Nov 12, 2015');
    }

    /** @test */
    function test_route_taskType_view_add_and_delete_a_record_route_back_to_task_view() {
        $this->visit('/task/show/1')
            ->click('#routeToTaskTypeView')
            ->see('Task Type Maintenance')
            ->type('Lunch', '#taskType01')
            ->type('Lunch break','description')
            ->tick('#taskType01')
            ->click('saveButtonTaskType')
            ->see('Lunch')
            ->click('Lunch')
            ->notSee('Lunch')
            ->click('#routeToTaskView')
            ->see('Nov 12, 2015');

    }

    /**
     * below tests test just calling the Task View directly, not via a route from Task View.
     */

    /** @test */
    function test_call_taskType_view() {
        $this->visit('/taskType/show/1')
            ->see('Task Type Maintenance');
    }

    /** @test */
    function test_call_taskType_view_notSee_route_to_task_view() {
        $this->visit('/taskType/show/1')
            ->see('Task Type Maintenance')
            ->notSee('routeToTaskView');
    }

    /** @test */
    function test_call_taskType_view_notSee_route_to_task_view_add_delete_refresh() {
        $this->visit('/taskType/show/1')
            ->see('Task Type Maintenance')
            ->notSee('routeToTaskView')
            ->type('Lunch', '#taskType01')
            ->type('Lunch break','description')
            ->tick('#taskType01')
            ->click('saveButtonTaskType')
            ->see('Lunch')
            ->click('Lunch')
            ->notSee('Lunch')
            ->click('#taskTypeRefreshPage')
            ->notSee('routeToTaskView');
    }


}