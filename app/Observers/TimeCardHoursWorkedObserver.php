<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/13/15
 * Time: 5:15 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;

class TimeCardHoursWorkedObserver
{
    public function created($timeCardHoursWorked)
    {
        appGlobals::createdMessage(appGlobals::getTimeCardHoursWorkedTableName(), $timeCardHoursWorked->date_worked, $timeCardHoursWorked->id);
    }
}
