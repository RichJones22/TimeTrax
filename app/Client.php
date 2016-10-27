<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Helpers\appGlobals;

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
    public static function checkIfExists($text)
    {
        $client = Client::where('name', '=', $text)->first();

        if (!is_null($client)) {
            appGlobals::existsMessage(appGlobals::getClientTableName(), $client->name, $client->id);
        }

        return $client;
    }

    /**
    * @return mixed
    */
    public function getName()
    {
        return $this->attributes['name'];
    }

    /**
    * @param $setName
    */
    public function setName($setName)
    {
        $this->attributes['name'] = $setName;
    }
}
