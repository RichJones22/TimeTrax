<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;
use \Carbon\Carbon;
use DB;

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

    static public function checkIfExists($inTimeCard) {

        $timeCard = TimeCard::where('iso_beginning_dow_date', '=', $inTimeCard->iso_beginning_dow_date)
            ->where('work_id', '=', $inTimeCard->work_id)
            ->first();

        if (is_null($timeCard)) {
            return $inTimeCard;
        } else {
            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_begninning_dow_date, $timeCard->work_id);
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
