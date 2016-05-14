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
    static public function checkIfExists($timeCardHoursWorked) {

        $timeCardHoursWorked = TimeCardHoursWorked::where('time_card_id', '=', $timeCardHoursWorked->time_card_id)->first();

        if (!is_null($timeCardHoursWorked)) {
            appGlobals::existsMessage(appGlobals::getTimeCardHoursWorkedTableName(), $timeCardHoursWorked->time_card_id, $timeCardHoursWorked->date_worked);
        }

        return $timeCardHoursWorked;
    }

    /**
     * @param [in/out] $inTimeCard
     * @return mixed
     */
    static public function checkIfDateWorkedDowExists($timeCardHoursWorked) {

        $timeCardHoursWorked = TimeCardHoursWorked::where('date_worked', $timeCardHoursWorked->date_worked)
            ->where('dow', $timeCardHoursWorked->dow)
            ->where('work_id', $timeCardHoursWorked->work_id)
            ->first();

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
    static public function deriveTimeCardHoursWorkedFromBeginningAndEndingWeekDates($timeCardRows, $iso_beginning_dow_date)
    {
        $hoursWorkedPerWorkId = [];

        // populate the time_card_hours_worked data by $timeCardRow->id.
        foreach ($timeCardRows as $timeCardRow) {
            $hoursWorkedPerWorkId = TimeCard::getHoursWorkedForTimeCard($iso_beginning_dow_date, $timeCardRow, $hoursWorkedPerWorkId);
        }
        return $hoursWorkedPerWorkId;
    }
}
