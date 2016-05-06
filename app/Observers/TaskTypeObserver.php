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
    public function deleting($taskType) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        $result = $taskType->checkTaskTypeDeleteAudits($taskType);
        if ($result > 0) {
            session()->forget(appGlobals::getInfoMessageType());
            session()->flash(appGlobals::getInfoMessageType(), sprintf(appGlobals::getInfoMessageText($result), $taskType->type));

            return false;
        }

        return true;
    }

    public function creating($taskType) {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            return true;
        }

        $result = $taskType->checkTaskTypeCreateAudits($taskType);
        if ($result > 0) {
            session()->forget(appGlobals::getInfoMessageType());
            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText($result));

            return false;
        }

        return true;
    }

    public function created($taskType) {
        appGlobals::createdMessage(appGlobals::getTaskTypeTableName(), $taskType->type , $taskType->id);
    }

    public function updating($taskType) {

        // check to see if the task_type.type exists.
        $result = $taskType->checkIfTypeExists($taskType);
        if ($result > 0) {
            session()->forget(appGlobals::getInfoMessageType());
            session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText($result));

            return false;
        }

        return true;
    }
}