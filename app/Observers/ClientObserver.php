<?php
namespace App\Observers;
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/8/15
 * Time: 1:05 PM
 */

class ClientObserver
{
    /**
     * post successful call to the save() method
     * @param $project
     */
    public function created($client) {
        createdMessage(clientTableName(), $client->name ,$client->id);
    }

}