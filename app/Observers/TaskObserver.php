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
        if ($task->checkIfTimeOverLaps($task->time_card_id, $task->start_time, $task->end_time)) {
            session()->flash('info_message', 'One of your entered time values overlaps with existing data.  Your data has been refreshed.');

            return false;
        }
    }

    public function created($task) {
        appGlobals::createdMessage(appGlobals::getTaskTableName(), $task->start_time , $task->id);
    }
}