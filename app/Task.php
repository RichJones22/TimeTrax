<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class Task extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'task';

    /**
     * fillable fields
     */
    protected $fillable = [
        'start_time',
        'end_type',
        'hours_worked',
        'notes'];

    static public function checkIfExists($startTime) {
        $task = Task::where('start_time', '=', $startTime)->first();

        if (!is_null($task)) {
            appGlobals::existsMessage(appGlobals::getTaskTableName(), $task->start_time, $task->id);
        }

        return $task;
    }
}
