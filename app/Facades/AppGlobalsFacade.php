<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 8/23/16
 * Time: 5:23 AM
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
class AppGlobalsFacade extends Facade{
    protected static function getFacadeAccessor() { return 'appglobals'; }
}