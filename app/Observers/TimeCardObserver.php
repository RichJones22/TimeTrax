<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/13/15
 * Time: 5:15 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;
class TimeCardObserver
{
    public function created($timeCard) {
        appGlobals::createdMessage(appGlobals::getTimeCardTableName(), $timeCard->work_id , $timeCard->id);
    }
}