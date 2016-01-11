<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

class TimeCardFormat extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'time_card_format';

    /**
     * fillable fields
     */
    protected $fillable = [
    'description',
    'dow_01',
    'dow_02',
    'dow_03',
    'dow_04',
    'dow_05',
    'dow_06',
    'dow_07'];

    static public function checkIfExists($text) {
        $timeCardFormat = TimeCardFormat::where('description', '=', $text)->first();

        if (!is_null($timeCardFormat)) {
            appGlobals::existsMessage(appGlobals::getTimeCardFormatTableName(), $timeCardFormat->description, $timeCardFormat->id);
        }

        return $timeCardFormat;
    }

    public function timeCard() {
        return $this->hasOne('\App\TimeCard');
    }
}
