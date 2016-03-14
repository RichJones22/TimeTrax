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

        $timeCard = TimeCard::where('date_worked', '=', $inTimeCard->date_worked)
            ->where('work_id', '=', $inTimeCard->work_id)
            ->first();

        if (!is_null($timeCard)) {
            $inTimeCard = $timeCard;

            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_begninning_dow_date, $timeCard->work_id);
        }

        return $timeCard;
    }

    /**
     * @param $bwDate
     * @param $ewDate
     * @return mixed
     */
    static public function getTimeCardRows($bwDate, $ewDate)
    {
        $timeCardRows = TimeCard::whereBetween('date_worked', [$bwDate, $ewDate])
            ->select('time_card.id', 'time_card.date_worked', 'time_card.work_id', 'time_card.time_card_format_id', 'time_card.hours_worked')
            ->get();
        return $timeCardRows;
    }

    /**
     * @param $iso_beginning_dow_date
     * @param $timeCardRow
     * @param $hoursWorkedPerWorkId
     * @return mixed
     */
//    static public function getHoursWorkedForTimeCard($bwDate, $ewDate, $timeCardRow, $hoursWorkedPerWorkId)
//    {
//        $hoursWorkedPerWorkId[$timeCardRow->id] = TimeCard::whereBetween('date_worked', [$bwDate, $ewDate])
//            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
//            ->where('time_card_hours_worked.hours_worked', ">", 0)
//            ->where('time_card_hours_worked.time_card_id', '=', $timeCardRow->id)
//            ->select('time_card.work_id'
//                , 'time_card_hours_worked.dow'
//                , 'time_card_hours_worked.hours_worked'
//                , 'time_card_hours_worked.id'
//                , 'time_card_hours_worked.date_worked')
//            ->get();
//        return $hoursWorkedPerWorkId;
//    }


}
