<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/13/15
 * Time: 5:15 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;
class TimeCardObserver
{
    public function creating($timeCard) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        if (!is_null($timeCard->checkIfExists($timeCard))) {
            session()->forget(appGlobals::getInfoMessageType());
            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));

            return false;
        }
    }

    public function created($timeCard) {
        appGlobals::createdMessage(appGlobals::getTimeCardTableName(), $timeCard->work_id , $timeCard->id);
    }
}