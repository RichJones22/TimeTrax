<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class TimeCardHoursWorked extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'time_card_hours_worked';

    /**
     * fillable fields
     */
    protected $fillable = [
        'date_worked',
        'dow',
        'hours_worked'];

    /**
     * @param [in/out] $inTimeCard
     * @return mixed
     */
    static public function checkIfExists(&$inTimeCard) {

        $timeCardHoursWorked = TimeCardHoursWorked::where('time_card_id', '=', $inTimeCard->id)->first();

        if (!is_null($timeCardHoursWorked)) {
            $inTimeCard = $timeCardHoursWorked;

            appGlobals::existsMessage(appGlobals::getTimeCardHoursWorkedTableName(), $timeCardHoursWorked->date_worked, $timeCardHoursWorked->id);
        }

        return $timeCardHoursWorked;
    }

    /**
     * @param [in/out] $inTimeCard
     * @return mixed
     */
    static public function checkIfDateWorkedDowExists(&$inTimeCard) {

        $timeCardHoursWorked = TimeCardHoursWorked::where('date_worked', '=', $inTimeCard->date_worked->toDateString())
                ->where('dow', '=', $inTimeCard->dow)->first();

        if (!is_null($timeCardHoursWorked)) {
            $inTimeCard = $timeCardHoursWorked;
        }

        return $timeCardHoursWorked;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function task() {
        return $this->hasMany('\App\Task');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCard() {
        return $this->belongsTo('\App\TimeCard');
    }

    /**
     * @param $timeCardRows
     * @param $bwDate
     * @param $ewDate
     * @return array
     */
//    static public function deriveTimeCardHoursWorkedFromBeginningAndEndingWeekDates($timeCardRows, $bwDate, $ewDate)
//    {
//        $hoursWorkedPerWorkId = [];
//
//        // populate the time_card_hours_worked data by $timeCardRow->id.
//        foreach ($timeCardRows as $timeCardRow) {
//            $hoursWorkedPerWorkId = TimeCard::getHoursWorkedForTimeCard($bwDate, $ewDate, $timeCardRow, $hoursWorkedPerWorkId);
//        }
//        return $hoursWorkedPerWorkId;
//    }
}
