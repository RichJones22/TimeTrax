<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/11/15
 * Time: 4:09 PM
 */

namespace app\Observers;

use \App\Helpers\appGlobals;

class WorkObserver
{
    public function created($work)
    {
        appGlobals::createdMessage(appGlobals::getWorkTableName(), $work->work_type_description, $work->id);
    }
}
