<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class Work extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'work';

    /**
     * fillable fields
     */
    protected $fillable = ['work_type_description'];

    static public function checkIfExists($text) {
        $work = Work::where('work_type_description', '=', $text)->first();

        if (!is_null($work)) {
            appGlobals::existsMessage(appGlobals::getWorkTableName(), $work->work_type_description, $work->id);
        }

        return $work;
    }

    public function workType() {
        return $this->belongsTo('\App\WorkType');
    }

    public function timeCard() {
        return $this->hasMany('\App\TimeCard');
    }
}
