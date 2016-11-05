<?php

namespace App;

use App\Helpers\appGlobals;

class TimeCardHoursWorked extends AppBaseModel
{
    /**
     *  table used by this model.
     */
    protected $table = 'time_card_hours_worked';

    /**
     * fillable fields.
     */
    protected $fillable = [
        'work_id',
        'date_worked',
        'dow',
        'hours_worked',
        'time_card_id', ];

    /**
     * @param $timeCardHoursWorked
     *
     * @return mixed
     */
    public static function checkIfExists($timeCardHoursWorked)
    {
        $timeCardHoursWorked = TimeCardHoursWorked::queryExec()
            ->where('time_card_id', '=', $timeCardHoursWorked->time_card_id)
            ->first();

        if (!is_null($timeCardHoursWorked)) {
            appGlobals::existsMessage(appGlobals::getTimeCardHoursWorkedTableName(), $timeCardHoursWorked->time_card_id, $timeCardHoursWorked->date_worked);
        }

        return $timeCardHoursWorked;
    }

    /**
     * @param $timeCardHoursWorked
     *
     * @return mixed
     */
    public static function checkIfDateWorkedDowExists($timeCardHoursWorked)
    {
        $timeCardHoursWorked = TimeCardHoursWorked::queryExec()
            ->where('date_worked', $timeCardHoursWorked->date_worked)
            ->where('dow', $timeCardHoursWorked->dow)
            ->where('work_id', $timeCardHoursWorked->work_id)
            ->first();

        return $timeCardHoursWorked;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function task()
    {
        return $this->hasMany('\App\Task');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeCard()
    {
        return $this->belongsTo('\App\TimeCard');
    }

    /**
     * @param $timeCardRows
     * @param $iso_beginning_dow_date
     *
     * @return array
     */
    public static function deriveTimeCardHoursWorkedFromBeginningAndEndingWeekDates($timeCardRows, $iso_beginning_dow_date)
    {
        $hoursWorkedPerWorkId = [];

        // populate the time_card_hours_worked data by $timeCardRow->id.
        foreach ($timeCardRows as $timeCardRow) {
            $hoursWorkedPerWorkId = TimeCard::getHoursWorkedForTimeCard($iso_beginning_dow_date, $timeCardRow, $hoursWorkedPerWorkId);
        }

        return $hoursWorkedPerWorkId;
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
    public function getDateWorked()
    {
        return $this->attributes['date_worked'];
    }

    /**
     * @param $setDateWorked
     */
    public function setDateWorked($setDateWorked)
    {
        $this->attributes['date_worked'] = $setDateWorked;
    }

    /**
     * @return mixed
     */
    public function getDow()
    {
        return $this->attributes['dow'];
    }

    /**
     * @param $setDow
     */
    public function setDow($setDow)
    {
        $this->attributes['dow'] = $setDow;
    }
    /**
     * @param $setHoursWorked
     */
    public function setHoursWorked($setHoursWorked)
    {
        $this->attributes['hours_worked'] = $setHoursWorked;
    }

    /**
     * @return mixed
     */
    public function getTimeCardId()
    {
        return $this->attributes['time_card_id'];
    }

    /**
     * @param $setTimeCardId
     */
    public function setTimeCardId($setTimeCardId)
    {
        $this->attributes['time_card_id'] = $setTimeCardId;
    }
}
