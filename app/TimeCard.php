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

    public $row=null;

    /**
     * fillable fields
     */
    protected $fillable = [
        'work_id',
        'time_card_format_id',
        'iso_beginning_dow_date'
    ];

    /**
     * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function work()
    {
        return $this->belongsTo('\App\Work');
    }

    /**
    * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCardFormat()
    {
        return $this->belongsTo('\App\TimeCardFormat');
    }

    /**
     * establish relations.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeCardHoursWorked()
    {
        return $this->hasMany('\App\TimeCardHoursWorked');
    }

    /**
     *
     * @param $inTimeCard by reference.  If time card found set $inTimeCard to found time card.  If not found don't
     *                                   $inTimeCard
     * @return mixed
     */
    public static function checkIfExists(&$inTimeCard)
    {

        $timeCard = TimeCard::where('work_id', $inTimeCard->work_id)
            ->where('time_card_format_id', '=', $inTimeCard->time_card_format_id)
            ->first();

        if (!is_null($timeCard)) {
            $inTimeCard = $timeCard;

            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_beginning_dow_date, $timeCard->id);
        }

        return $timeCard;
    }

    /**
     * @param TimeCard $timeCard
     * @return bool
     */
    public static function doesTimeCardExist(TimeCard &$inTimeCard)
    {

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


    public function rowExists()
    {
        $this->row = TimeCard::where('work_id', '=', $this->work_id)
            ->where('iso_beginning_dow_date', '=', $this->iso_beginning_dow_date)
            ->first();

        return $this->row ? true : false;
    }

    /**
     * @param $bwDate
     * @param $ewDate
     * @return mixed
     */
    public static function getTimeCardRows($iso_beginning_dow_date)
    {
        $timeCardRows = TimeCard::where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
            ->join('work', 'work.id', '=', 'time_card.work_id')
            ->join('work_type', 'work_type.id', '=', 'work.work_type_id')
            ->select('time_card.id', 'time_card.iso_beginning_dow_date', 'time_card.work_id', 'time_card.time_card_format_id')
            ->orderBy('work_type.type')
            ->get();
        return $timeCardRows;
    }

    /**
     * @param $iso_beginning_dow_date
     * @param $timeCardRow
     * @param $hoursWorkedPerWorkId
     * @return mixed
     */
    public static function getHoursWorkedForTimeCard($iso_beginning_dow_date, $timeCardRow, $hoursWorkedPerWorkId)
    {
        $hoursWorkedPerWorkId[$timeCardRow->id] = TimeCard::where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->where('time_card_hours_worked.hours_worked', ">", 0)
            ->where('time_card_hours_worked.time_card_id', '=', $timeCardRow->id)
            ->select('time_card.work_id', 'time_card_hours_worked.dow', 'time_card_hours_worked.hours_worked', 'time_card_hours_worked.id', 'time_card_hours_worked.date_worked')
            ->get();
        return $hoursWorkedPerWorkId;
    }
}
