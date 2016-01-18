<?php

namespace App;

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

    static public function checkIfExists($timeCard) {

        $timeCardHoursWorked = TimeCardHoursWorked::where('time_card_id', '=', $timeCard->id)->first();

        if (!is_null($timeCardHoursWorked)) {
            appGlobals::existsMessage(appGlobals::getTimeCardHoursWorkedTableName(), $timeCardHoursWorked->date_worked, $timeCardHoursWorked->id);
        }

        return $timeCardHoursWorked;
    }

    /**
     * Eager load Task model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function task() {
        return $this->hasMany('\App\Task');
    }

    public function timeCard() {
        return $this->belongsTo('\App\TimeCard');
    }
}
