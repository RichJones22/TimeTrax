<?php namespace App;

use App\Helpers\appGlobals;

class Client extends AppBaseModel
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
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public static function checkIfExists($text)
    {
        $client = Client::queryExec()
            ->where('name', '=', $text)
            ->first();

        if (!is_null($client)) {
            appGlobals::existsMessage(appGlobals::getClientTableName(), $client->name, $client->id);
        }

        return $client;
    }
}
