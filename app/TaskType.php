<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskType.
 */
class TaskType extends Model
{
    /**
     *  table used by this model.
     */
    protected $table = 'task_type';

    /**
     * fillable fields.
     */
    protected $fillable = [
        'type',
        'description',
    ];

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->attributes['id'] = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->attributes['id'];
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->attributes['type'];
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->attributes['type'] = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->attributes['description'];
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->attributes['client_id'];
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->attributes['description'] = $description;

        return $this;
    }

    /**
     * check if type exists.
     *
     * @param $type
     *
     * @return mixed
     */
    public static function checkIfExists($type)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $taskType = self::where('type', '=', $type)->first();

        if (!is_null($taskType)) {
            appGlobals()->existsMessage(appGlobals()->getTaskTypeTableName(), $taskType->type, $taskType->id);
        }

        return $taskType;
    }

    /**
     * Eager load Task model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function task()
    {
        return $this->hasMany('\App\Task');
    }

    /**
     * Delete edit and audit routine(s).
     *
     * @param $taskType
     *
     * @return bool
     */
    public function checkTaskTypeDeleteAudits($taskType)
    {
        if (($result = $this->checkIfTypeConstraintExists($taskType)) > 0) {
            return $result;
        }

        return false;
    }

    /**
     * Create edit and audit routine(s).
     *
     * @param $taskType
     *
     * @return bool
     */
    public function checkTaskTypeCreateAudits($taskType)
    {
        if (($result = $this->checkIfTypeExists($taskType)) > 0) {
            return $result;
        }

        if (($result = $this->checkIfTypeContainsMultipleWords($taskType)) > 0) {
            return $result;
        }

        return false;
    }

    /**
     * @param $taskType
     *
     * @return int|mixed
     */
    public function checkIfTypeExists($taskType)
    {

        /* @noinspection PhpUndefinedMethodInspection */
        $val = self::where('type', '=', $taskType->type)
            ->where('client_id', '=', $taskType->client_id)
            ->first();

        if (!is_null($val)) {
            return appGlobals()::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS;
        }

        return 0;
    }

    /**
     * @param $taskType
     *
     * @return int
     */
    public function checkIfTypeContainsMultipleWords($taskType)
    {
        $val = explode(' ', trim($taskType->type));

        if (count($val) > 1) {
            return (int) appGlobals()::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD;
        }

        return 0;
    }

    /**
     * @param $taskType
     *
     * @return int|mixed
     */
    public function checkIfTypeConstraintExists($taskType)
    {

        /* @noinspection PhpUndefinedMethodInspection */
        $val = Task::where('task_type_id', '=', $taskType->id)
            ->first();

        if (!is_null($val)) {
            return appGlobals()::TBL_TASK_TYPE_CONSTRAINT_VIOLATION;
        }

        return 0;
    }

    /**
     * @param $att
     */
    public function updateRec($att)
    {
        $changed = false;

        $arrUpdate = [];

        $taskType = TaskType::where('id', '=', $att->id)->first();

        // if taskType exists, create an update array of those fields that changed; then update the record.
        if ($taskType) {
            if (TaskType::getType() !== $att->type) {
                $arrUpdate['type'] = $att->type;
                $changed = true;
            }
            if (TaskType::getDescription() !== $att->desc) {
                $arrUpdate['description'] = $att->desc;
                $changed = true;
            }
            // currently only one client; update this when there are two clients.
//            if (self::getClientId() !== $att->client_id) {
//                $arrUpdate['client_id'] = $att->client_id;
//                $changed = true;
//            }

            if ($changed) {
                /* @noinspection PhpUndefinedClassInspection */
                \DB::table('task_type')
                    ->where('id', $att->id)
                    ->update($arrUpdate);
            }
        }
    }
}
