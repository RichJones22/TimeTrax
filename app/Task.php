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


    static private function getStartTime($startTime) {
        return Task::where('start_time', '=', $startTime)->first();
    }

    static public function checkIfExists($startTime) {
        $task = self::getStartTime($startTime);

        if (!is_null($task)) {
            appGlobals::existsMessage(appGlobals::getTaskTableName(), $task->start_time, $task->id);
        }

        return $task;
    }


    /**
     *  check for time overlaps
     *
     * @param $timeCardId
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    public function checkIfTimeOverLaps($timeCardId, $startTime, $endTime) {

        if ($this->checkIfStartTimeExists($timeCardId, $startTime) ||
            $this->checkIfStartTimeOverLaps($timeCardId, $startTime) ||
            $this->checkIfEndTimeOverLaps($timeCardId, $endTime)) {
            return true;
        }

        return false;
    }

    public function checkIfStartTimeExists($timeCardId, $startTime) {

        $val = Task::where('start_time', '=', $startTime)
            ->where('time_card_id', '=', $timeCardId)
            ->first();

        if (is_null($val)) {
            return false;
        }

        return true;
    }

    public function checkIfStartTimeOverLaps($timeCardId, $startTime) {

        $val = Task::where('time_card_id', '=', $timeCardId)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>', $startTime)
            ->first();

        if (is_null($val)) {
            return false;
        }

        return true;
    }

    public function checkIfEndTimeOverLaps($timeCardId, $endTime) {

        $val = Task::where('time_card_id', '=', $timeCardId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>=', $endTime)
            ->first();

        if (is_null($val)) {
            return false;
        }

        return true;
    }

    /**
     * Eager load the TimeCard model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCardHoursWorked() {
        return $this->belongsTo('\App\TimeCardHoursWorked');
    }

    /**
     * Eager load the TaskType model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskType() {
        return $this->belongsTo('\App\TaskType');
    }
}
