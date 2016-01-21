<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/14/15
 * Time: 8:47 AM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;
class TaskObserver
{
    public function creating($task) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        if ($task->checkIfTimeOverLaps($task->time_card_hours_worked_id, $task->start_time, $task->end_time)) {
            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));

            return false;
        }
    }

    public function created($task) {
        appGlobals::createdMessage(appGlobals::getTaskTableName(), $task->start_time , $task->id);
    }
}