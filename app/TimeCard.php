<?php

namespace App;

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
        'work_id'];


//    /**
//     * pseudo polymorphic call.
//     *
//     * usage:
//     *  - if $returnInTimeCard is true, then return the timeCard record that was passed in.  if false then return
//     *    the timeCard record that was read.
//     *
//     *  - in some cases, you just want to check if the record exists, and not overwrite your timeCard data if it does
//     *    not exist.  In this case, you would set $returnInTimeCard = true by the caller.
//     *
//     *  - there are two returns for this method.  The last return will only get called if $timeCard is not null.
//     *    therefore, the ternary operator is moot for that call.
//     *
//     * @param $inTimeCard
//     * @param bool $returnInTimeCard
//     * @return mixed
//     */
//    static public function checkIfExists($inTimeCard, $returnInTimeCard = false) {
//
//        $timeCard = TimeCard::where('iso_beginning_dow_date', '=', $inTimeCard->iso_beginning_dow_date)
//            ->where('work_id', '=', $inTimeCard->work_id)
//            ->first();
//
//        if (is_null($timeCard)) {
//            return $returnInTimeCard ? $inTimeCard : $timeCard;
//        } else {
//            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_begninning_dow_date, $timeCard->work_id);
//        }
//
//        return $timeCard;
//    }

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
     * establish relations.
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
