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

}
