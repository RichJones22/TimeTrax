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
    public function created($taskType) {
        appGlobals::createdMessage(appGlobals::getTaskTypeTableName(), $taskType->type , $taskType->id);
    }
}