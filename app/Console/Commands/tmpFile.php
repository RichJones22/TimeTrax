<?php

namespace App\Console\Commands;

use \App\Client;

class MyClass extends Client
{

    public function getFillableArr()
    {
        return parent::getFillable();
    }
}
