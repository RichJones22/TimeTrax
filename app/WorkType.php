<?php namespace App;

use \App\Helpers\appGlobals;
use Illuminate\Database\Eloquent\Builder;

class WorkType extends AppBaseModel
{
    /**
     *  table used by this model
     */
    protected $table = 'work_type';

    /**
     * fillable fields
     */
    protected $fillable = ['type'];

    /**
     * reads Project table by unique index
     *  - if not found, emit a not found message.
     *  - if found return the $project record to the caller.
     *
     * @param [in] $text
     * @return Builder.
     */
    public static function checkIfExists($text)
    {
        $workType = WorkType::queryExec()
            ->where('type', '=', $text)
            ->first();

        if (!is_null($workType)) {
            appGlobals::existsMessage(appGlobals::getWorkTypeTableName(), $workType->type, $workType->id);
        }

        return $workType;
    }

    public function work()
    {
        return $this->hasMany(Work::class);
    }
}
