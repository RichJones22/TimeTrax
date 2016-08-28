<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 11/14/15
 * Time: 7:05 AM
 */

namespace App\Observers;

use App\TaskType;

class TaskTypeObserver
{
    public function deleting(TaskType $taskType)
    {
        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals()->getTestRDBMS()) {
            return true;
        }

        $result = $taskType->checkTaskTypeDeleteAudits($taskType);
        if ($result > 0) {
            session()->forget(appGlobals()->getInfoMessageType());
            session()->flash(appGlobals()
                ->getInfoMessageType(), sprintf(appGlobals()->getInfoMessageText($result), $taskType->getType()));

            return false;
        }

        return true;
    }

    public function creating(TaskType  $taskType)
    {
        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals()->getTestRDBMS()) {
            // this log statement is needed to make testCreatingDataThatAlreadyExists() pass in
            // TaskTypeView/testTaskTypeRDBMS.php; this is a mystery as to why...
            MyLog()->info("I was creating", ['taskType' => $taskType]);

            return true;
        }

        $result = $taskType->checkTaskTypeCreateAudits($taskType);
        if ($result > 0) {
            session()->forget(appGlobals()->getInfoMessageType());
            session()->flash(appGlobals()->getInfoMessageType(), appGlobals()->getInfoMessageText($result));

            return false;
        }

        return true;
    }

    public function created(TaskType $taskType)
    {
        appGlobals()->createdMessage(appGlobals()->getTaskTypeTableName(), $taskType->getType(), $taskType->getId());
    }

    public function updating(TaskType $taskType)
    {
        // check to see if the task_type.type exists.
        $result = $taskType->checkIfTypeExists($taskType);
        if ($result > 0) {
            session()->forget(appGlobals()->getInfoMessageType());
            session()->flash(appGlobals()->getInfoMessageType(), appGlobals()->getInfoMessageText($result));

            return false;
        }

        return true;
    }
}
