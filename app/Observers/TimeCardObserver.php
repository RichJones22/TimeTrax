<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/13/15
 * Time: 5:15 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;
use App\TimeCard;

class TimeCardObserver
{
    public function creating(TimeCard $timeCard) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        if (!is_null($timeCard->checkIfExists($timeCard))) {
           return new \Exception();
        }

        return true;
    }

    public function created($timeCard) {
        appGlobals::createdMessage(appGlobals::getTimeCardTableName(), $timeCard->work_id , $timeCard->id);
    }
}