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
        'time_card_id',
        'task_type_id',
        'start_time',
        'end_time',
        'hours_worked',
        'notes'];

    static public function checkIfExists($startTime) {
        $task = Task::where('start_time', '=', $startTime)->first();

        if (!is_null($task)) {
            appGlobals::existsMessage(appGlobals::getTaskTableName(), $task->start_time, $task->id);
        }

        return $task;
    }

    /**
     * Eager load the TimeCard model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCard() {
        return $this->belongsTo('\App\TimeCard');
    }

    /**
     * Eager load the TaskType model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskType() {
        return $this->belongsTo('\App\TaskType');
    }
}
