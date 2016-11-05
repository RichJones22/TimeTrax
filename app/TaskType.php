<?php

namespace App;

/**
 * Class TaskType.
 */
class TaskType extends AppBaseModel
{
    /**
     *  table used by this model.
     */
    protected $table = 'task_type';

    /**
     * fillable fields.
     */
    protected $fillable = [
        'id',
        'type',
        'description',
        'client_id',
    ];

   /**
     * check if type exists.
     *
     * @param $type
     *
     * @return mixed
     */
    public static function checkIfExists($type)
    {
        $taskType = TaskType::queryExec()
            ->where('type', '=', $type)
            ->first();

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
        $val = TaskType::queryExec()
            ->where('type', '=', $taskType->type)
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
            return (int)appGlobals()::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD;
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

        $val = Task::queryExec()
            ->where('task_type_id', '=', $taskType->id)
            ->first();

        if (!is_null($val)) {
            return appGlobals()::TBL_TASK_TYPE_CONSTRAINT_VIOLATION;
        }

        return 0;
    }

    /**
     * - get TaskType by id
     * - if either Type or Description have changed, update the record.
     *
     * @param $att
     */
    public function updateRec($att)
    {
        if ($taskType = $this->getTaskTypeById($att)) {
            if ($taskType->getType() !== $att->type) {
                $taskType->setType($att->type);

                $this->updateTaskTypeById($taskType);
            } elseif ($taskType->getDescription() !== $att->desc) {
                $taskType->setDescription($att->desc);

                $this->updateTaskTypeById($taskType);
            }
        }
    }

    /**
     * @param $att
     * @return $this|null
     */
    protected function getTaskTypeById($att)
    {
        try {
            $attributes = TaskType::queryExec()
                ->where('id', $att->id)
                ->first()
                ->attributes;

            return $this->getModel()->fill($attributes);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * - find TaskType record by id
     * - if found, update the row with taskType->attributes.
     *
     * @param $taskType
     */
    protected function updateTaskTypeById($taskType)
    {
        TaskType::queryExec()
            ->where('id', $taskType->id)
            ->update($taskType->attributes);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id)
    {
        return $this->attributes['id'] = $id;
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
     * @param $setType
     */
    public function setType($setType)
    {
        $this->attributes['type'] = $setType;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->attributes['description'];
    }

    /**
     * @param $setDescription
     */
    public function setDescription($setDescription)
    {
        $this->attributes['description'] = $setDescription;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->attributes['client_id'];
    }

    /**
     * @param $setClientId
     */
    public function setClientId($setClientId)
    {
        $this->attributes['client_id'] = $setClientId;
    }
}