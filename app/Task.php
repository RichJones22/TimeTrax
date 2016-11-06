<?php namespace App;

use App\Helpers\appGlobals;

/**
 * Class Task
 * @package App
 */
class Task extends AppBaseModel
{
    /**
     *  table used by this model
     */
    protected $table = 'task';

    /**
     * fillable fields
     */
    protected $fillable = [
        'time_card_hours_worked_id',
        'task_type_id',
        'start_time',
        'end_time',
        'hours_worked',
        'notes'];


    /**
     * @param $startTime
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function getStartTime($startTime)
    {
        return Task::queryExec()
            ->where('start_time', '=', $startTime)
            ->first();
    }

    /**
     * @param $startTime
     * @return Task|\Illuminate\Database\Eloquent\Model|null
     */
    public static function checkIfExists($startTime)
    {
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
    public function checkIfTimeOverLaps($timeCardId, $startTime, $endTime)
    {
        if ($this->checkIfStartTimeExists($timeCardId, $startTime) ||
            $this->checkIfStartTimeOverLaps($timeCardId, $startTime) ||
            $this->checkIfEndTimeOverLaps($timeCardId, $endTime)) {
            return true;
        }

        return false;
    }

    /**
     * @param $timeCardId
     * @param $startTime
     * @return bool
     */
    public function checkIfStartTimeExists($timeCardId, $startTime)
    {
        $val = Task::queryExec()
            ->where('start_time', '=', $startTime)
            ->where('time_card_hours_worked_id', '=', $timeCardId)
            ->first();

        if (is_null($val)) {
            return false;
        }

        return true;
    }

    /**
     * @param $timeCardId
     * @param $startTime
     * @return bool
     */
    public function checkIfStartTimeOverLaps($timeCardId, $startTime)
    {
        $val = Task::queryExec()
            ->where('time_card_hours_worked_id', '=', $timeCardId)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>', $startTime)
            ->first();

        if (is_null($val)) {
            return false;
        }

        return true;
    }

    /**
     * @param $timeCardId
     * @param $endTime
     * @return bool
     */
    public function checkIfEndTimeOverLaps($timeCardId, $endTime)
    {
        $val = Task::queryExec()
            ->where('time_card_hours_worked_id', '=', $timeCardId)
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
    public function timeCardHoursWorked()
    {
        return $this->belongsTo('\App\TimeCardHoursWorked');
    }

    /**
     * Eager load the TaskType model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskType()
    {
        return $this->belongsTo('\App\TaskType');
    }

    /**
    * @return mixed
    */
    public function getTimeCardHoursWorkedId()
    {
        return $this->attributes['time_card_hours_worked_id'];
    }

    /**
    * @param $setTimeCardHoursWorkedId
    */
    public function setTimeCardHoursWorkedId($setTimeCardHoursWorkedId)
    {
        $this->attributes['time_card_hours_worked_id'] = $setTimeCardHoursWorkedId;
    }

    /**
    * @return mixed
    */
    public function getTaskTypeId()
    {
        return $this->attributes['task_type_id'];
    }

    /**
    * @param $setTaskTypeId
    */
    public function setTaskTypeId($setTaskTypeId)
    {
        $this->attributes['task_type_id'] = $setTaskTypeId;
    }
    /**
    * @param $setStartTime
    */
    public function setStartTime($setStartTime)
    {
        $this->attributes['start_time'] = $setStartTime;
    }

    /**
    * @return mixed
    */
    public function getEndTime()
    {
        return $this->attributes['end_time'];
    }

    /**
    * @param $setEndTime
    */
    public function setEndTime($setEndTime)
    {
        $this->attributes['end_time'] = $setEndTime;
    }

    /**
    * @return mixed
    */
    public function getHoursWorked()
    {
        return $this->attributes['hours_worked'];
    }

    /**
    * @param $setHoursWorked
    */
    public function setHoursWorked($setHoursWorked)
    {
        $this->attributes['hours_worked'] = $setHoursWorked;
    }

    /**
    * @return mixed
    */
    public function getNotes()
    {
        return $this->attributes['notes'];
    }

    /**
    * @param $setNotes
    */
    public function setNotes($setNotes)
    {
        $this->attributes['notes'] = $setNotes;
    }
}
