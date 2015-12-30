<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class TaskType extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'task_type';

    /**
     * fillable fields
     */
    protected $fillable = [
        'type',
        'description'];

    static public function checkIfExists($data) {
        $taskType = TaskType::where('type', '=', $data)->first();

        if (!is_null($taskType)) {
            appGlobals::existsMessage(appGlobals::getTaskTypeTableName(), $taskType->type, $taskType->id);
        }

        return $taskType;
    }

    /**
     * Eager load Task model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function task() {
        return $this->hasMany('\App\Task');
    }

    /**
     * Delete edit and audit routine(s).
     * @param $taskType
     * @return bool
     */
    public function checkTaskTypeDeleteAudits($taskType) {

        if (($result =  $this->checkIfTypeConstraintExists($taskType)) > 0) {
            return $result;
        }

        return false;
    }

    /**
     * Create edit and audit routine(s).
     * @param $taskType
     * @return bool
     */
    public function checkTaskTypeCreateAudits($taskType) {

        if (($result =  $this->checkIfTypeExists($taskType)) > 0) {
            return $result;
        }

        if (($result =  $this->checkIfTypeContainsMultipleWords($taskType)) > 0) {
            return $result;
        }

        return false;
    }

    public function checkIfTypeExists($taskType) {

        $val = TaskType::where('type', '=', $taskType->type)
            ->where('client_id', '=', $taskType->client_id)
            ->first();

        if (!is_null($val)) {
            return appGlobals::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS;
        }

        return 0;
    }

    public function checkIfTypeContainsMultipleWords($taskType) {

        $val[] = explode(" ", trim($taskType->type));

        if (count($val) > 1) {
            return (int)appGlobals::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD;
        }

        return 0;
    }

    public function checkIfTypeConstraintExists($taskType) {

        $val = Task::where('task_type_id', '=', $taskType->id)
            ->first();

        if (!is_null($val)) {
            return appGlobals::TBL_TASK_TYPE_CONSTRAINT_VIOLATION;
        }

        return 0;
    }
}
