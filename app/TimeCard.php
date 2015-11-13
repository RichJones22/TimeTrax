<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

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
        'date_worked',
        'dow',
        'total_hours_worked'];

    static public function checkIfExists($data) {
        $timeCard = TimeCard::where('date_worked', '=', $data)->first();

        if (!is_null($timeCard)) {
            appGlobals::existsMessage(appGlobals::getTimeCardTableName(), $timeCard->date_worked, $timeCard->id);
        }

        return $timeCard;
    }
}
