<?php
namespace App\Observers;
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/8/15
 * Time: 3:55 PM
 */
use \App\Helpers\appGlobals;
class WorkTypeObserver
{
    /**
     * post successful call to the save() method
     * @param $project
     */
    public function created($workType) {
        appGlobals::createdMessage(appGlobals::getWorkTypeTableName(), $workType->type, $workType->id);
    }
}