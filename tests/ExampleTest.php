<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Welcome to TimeTrax Dev');
    }

//    public function testTaskShow()
//    {
//        $this->get('/task/show/1')->see('Type')->see('Start')->see('Time');
//    }
}
