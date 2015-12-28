<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/14/15
 * Time: 7:05 AM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;

class TaskTypeObserver
{
    public function creating($taskType) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        $result = $taskType->checkTaskTypeAudits($taskType);
        if ($result > 0) {
            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText($result));

            return false;
        }
//
//        if ($result = $taskType->checkTaskTypeAudits($taskType)) {
//            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText($result));
//
//            return false;
//        }
    }

    public function created($taskType) {
        appGlobals::createdMessage(appGlobals::getTaskTypeTableName(), $taskType->type , $taskType->id);
    }
}