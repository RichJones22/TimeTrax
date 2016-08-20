<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/10/15
 * Time: 6:47 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;

class TimeCardFormatObserver
{
    public function created($timeCardFormat)
    {
        appGlobals::createdMessage(appGlobals::getTimeCardFormatTableName(), $timeCardFormat->description, $timeCardFormat->id);
    }
}
