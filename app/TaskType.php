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
}
