<?php namespace App;

use \App\Helpers\appGlobals;

class Work extends AppBaseModel
{
    /**
     *  table used by this model
     */
    protected $table = 'work';

    /**
     * fillable fields
     */
    protected $fillable = ['work_type_description'];

    public static function checkIfExists($text)
    {
        $work = Work::queryExec()
            ->where('work_type_description', '=', $text)
            ->first();

        if (!is_null($work)) {
            appGlobals::existsMessage(appGlobals::getWorkTableName(), $work->work_type_description, $work->id);
        }

        return $work;
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    public function timeCard()
    {
        return $this->hasMany(TimeCard::class);
    }
}
