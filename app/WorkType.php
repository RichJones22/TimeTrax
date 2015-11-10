<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class WorkType extends Model
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
     * @return record.
     */
    static public function checkIfExists($text) {
        $workType = WorkType::where('type', '=', $text)->first();

        if (!is_null($workType)) {
            appGlobals::existsMessage(appGlobals::getWorkTypeTableName(), $workType->type, $workType->id);
        }

        return $workType;
    }
}
