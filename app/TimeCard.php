<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;
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
        'work_id'
    ];

    /**
     * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function work() {
        return $this->belongsTo('\App\Work');
    }

    /**
    * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCardFormat() {
        return $this->belongsTo('\App\TimeCardFormat');
    }

    /**
     * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeCardHoursWorked() {
        return $this->hasMany('\App\TimeCardHoursWorked');
    }

    /**
     *
     * @param $inTimeCard by reference.  If time card found set $inTimeCard to found time card.  If not found don't
     *                                   $inTimeCard
     * @return mixed
     */
    static public function checkIfExists(&$inTimeCard) {

        $timeCard = TimeCard::where('iso_beginning_dow_date', '=', $inTimeCard->iso_beginning_dow_date)
            ->where('work_id', '=', $inTimeCard->work_id)
            ->first();

        if (!is_null($timeCard)) {
            $inTimeCard = $timeCard;

            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_begninning_dow_date, $timeCard->work_id);
        }

        return $timeCard;
    }

    /**
     * @param TimeCard $timeCard
     * @return bool
     */
    static public function doesTimeCardExist(TimeCard &$inTimeCard) {

        $timeCard = TimeCard::where('iso_beginning_dow_date', '=', $inTimeCard->iso_beginning_dow_date)
            ->where('work_id', '=', $inTimeCard->work_id)
            ->first();

        if (is_null($timeCard)) {
            return false;
        } else {
            $inTimeCard = $timeCard;
            return true;
        }
    }

    /**
     * @param $bwDate
     * @param $ewDate
     * @return mixed
     */
    static public function getTimeCardRows($iso_beginning_dow_date)
    {
        $timeCardRows = TimeCard::where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
            ->select('time_card.id', 'time_card.iso_beginning_dow_date', 'time_card.work_id', 'time_card.time_card_format_id')
            ->get();
        return $timeCardRows;
    }

    /**
     * @param $iso_beginning_dow_date
     * @param $timeCardRow
     * @param $hoursWorkedPerWorkId
     * @return mixed
     */
    static public function getHoursWorkedForTimeCard($iso_beginning_dow_date, $timeCardRow, $hoursWorkedPerWorkId)
    {
        $hoursWorkedPerWorkId[$timeCardRow->id] = TimeCard::where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->where('time_card_hours_worked.hours_worked', ">", 0)
            ->where('time_card_hours_worked.time_card_id', '=', $timeCardRow->id)
            ->select('time_card.work_id'
                , 'time_card_hours_worked.dow'
                , 'time_card_hours_worked.hours_worked'
                , 'time_card_hours_worked.id'
                , 'time_card_hours_worked.date_worked')
            ->get();
        return $hoursWorkedPerWorkId;
    }


}
