<?php
namespace App\Observers;
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/8/15
 * Time: 1:05 PM
 */

use \App\Helpers\appGlobals;

class ClientObserver
{
    /**
     * post successful call to the save() method
     * @param $project
     */
    public function created($client) {
        appGlobals::createdMessage(appGlobals::getClientTableName(), $client->name , $client->id);
    }

}