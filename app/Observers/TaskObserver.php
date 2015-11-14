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
    public function created($task) {
        appGlobals::createdMessage(appGlobals::getTaskTableName(), $task->start_time , $task->id);
    }
}