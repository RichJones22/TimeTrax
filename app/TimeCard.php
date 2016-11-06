<?php namespace App;

use App\Helpers\appGlobals;

class TimeCard extends AppBaseModel
{
    /**
     *  table used by this model.
     */
    protected $table = 'time_card';

    public $row = null;

    /**
     * fillable fields.
     */
    protected $fillable = [
        'iso_beginning_dow_date',
        'work_id',
        'time_card_format_id',
    ];

    /**
     * establish relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * establish relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCardFormat()
    {
        return $this->belongsTo(TimeCardFormat::class);
    }

    /**
     * establish relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeCardHoursWorked()
    {
        return $this->hasMany(TimeCardHoursWorked::class);
    }

    /**
     * If time card found set $inTimeCard to found time card.  If not found don't $inTimeCard.
     *
     * @param $inTimeCard
     *
     * @return mixed
     */
    public static function checkIfExists(&$inTimeCard)
    {
        $timeCard = TimeCard::queryExec()
            ->where('work_id', $inTimeCard->work_id)
            ->where('time_card_format_id', '=', $inTimeCard->time_card_format_id)
            ->first();

        if (!is_null($timeCard)) {
            $inTimeCard = $timeCard;

            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->iso_beginning_dow_date, $timeCard->id);
        }

        return $timeCard;
    }

    /**
     * @param TimeCard $inTimeCard
     *
     * @return bool
     *
     * @internal param TimeCard $timeCard
     */
    public static function doesTimeCardExist(TimeCard &$inTimeCard)
    {
        $timeCard = TimeCard::queryExec()
            ->where('iso_beginning_dow_date', '=', $inTimeCard->getIsoBeginningDowDate())
            ->where('work_id', '=', $inTimeCard->getWorkId())
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
        $this->row = TimeCard::queryExec()
            ->where('work_id', '=', $this->getWorkId())
            ->where('iso_beginning_dow_date', '=', $this->getIsoBeginningDowDate())
            ->first();

        return $this->row ? true : false;
    }

    /**
     * @param $iso_beginning_dow_date
     *
     * @return mixed
     *
     * @internal param $bwDate
     * @internal param $ewDate
     */
    public static function getTimeCardRows($iso_beginning_dow_date)
    {
        $timeCardRows = TimeCard::getModel()
            ->newQuery()
            ->where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
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
     *
     * @return mixed
     */
    public static function getHoursWorkedForTimeCard($iso_beginning_dow_date, $timeCardRow, $hoursWorkedPerWorkId)
    {
        $hoursWorkedPerWorkId[$timeCardRow->id] = TimeCard::getModel()
            ->newQuery()
            ->where('iso_beginning_dow_date', '=', $iso_beginning_dow_date)
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->where('time_card_hours_worked.hours_worked', '>', 0)
            ->where('time_card_hours_worked.time_card_id', '=', $timeCardRow->id)
            ->select('time_card.work_id', 'time_card_hours_worked.dow', 'time_card_hours_worked.hours_worked', 'time_card_hours_worked.id', 'time_card_hours_worked.date_worked')
            ->get();

        return $hoursWorkedPerWorkId;
    }

    /**
     * @return mixed
     */
    public function getIsoBeginningDowDate()
    {
        return $this->attributes['iso_beginning_dow_date'];
    }

    /**
     * @param $setIsoBeginningDowDate
     */
    public function setIsoBeginningDowDate($setIsoBeginningDowDate)
    {
        $this->attributes['iso_beginning_dow_date'] = $setIsoBeginningDowDate;
    }

    /**
     * @return mixed
     */
    public function getWorkId()
    {
        return $this->attributes['work_id'];
    }

    /**
     * @param $setWorkId
     */
    public function setWorkId($setWorkId)
    {
        $this->attributes['work_id'] = $setWorkId;
    }

    /**
     * @return mixed
     */
    public function getTimeCardFormatId()
    {
        return $this->attributes['time_card_format_id'];
    }

    /**
     * @param $setTimeCardFormatId
     */
    public function setTimeCardFormatId($setTimeCardFormatId)
    {
        $this->attributes['time_card_format_id'] = $setTimeCardFormatId;
    }
}
