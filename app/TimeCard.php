<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class TimeCard extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'time_card';

    /**
     * fillable fields
     */
    protected $fillable = [
        'work_id'];

    static public function checkIfExists($work) {

        $timeCard = TimeCard::where('work_id', '=', $work->id)->first();

        if (!is_null($timeCard)) {
            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->work_id, $timeCard->id);
        }

        return $timeCard;
    }

    /**
     * Eager load Task model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function work() {
        return $this->belongsTo('\App\Work');
    }

    public function timeCardFormat() {
        return $this->belongsTo('\App\TimeCardFormat');
    }

    public function timeCardHoursWorked() {
        return $this->hasMany('\App\TimeCardHoursWorked');
    }

}
