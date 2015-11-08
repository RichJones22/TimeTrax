<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//require_once(__DIR__ . '/Helpers/Helper.php');

class Client extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'client';

    /**
     * fillable fields
     */
    protected $fillable = ['name'];

    /**
     * reads Project table by unique index
     *  - if not found, emit a not found message.
     *  - if found return the $project record to the caller.
     *
     * @param [in] $text
     * @return a record.
     */
    static public function checkIfExists($text) {
        $client = Client::where('name', '=', $text)->first();

        if (!is_null($client)) {
            existsMessage($client->table, $client->name, $client->id);
        }

        return $client;
    }




}
