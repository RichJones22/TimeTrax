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
     * @param $bwDate
     * @param $ewDate
     * @return mixed
     */
    static public function getTimeCardRows(Carbon $bwDate, Carbon $ewDate)
    {
        $timeCardRows = TimeCard::whereBetween('time_card_hours_worked.date_worked', [$bwDate, $ewDate])
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->join('work', 'time_card.work_id', '=', 'work.id')
            ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
            ->where('time_card_hours_worked.hours_worked', ">", 0)
            ->select('time_card.id', 'time_card.iso_beginning_dow_date', 'time_card.work_id', 'time_card.time_card_format_id')
            ->groupBy('work_type.type')
            ->orderBy('work_type.type')
            ->get();
        return $timeCardRows;
    }

}
