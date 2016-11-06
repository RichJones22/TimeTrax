<?php namespace App;

use App\Helpers\appGlobals;

class Project extends AppBaseModel
{
    /**
     *  table used by this model
     */
    protected $table = 'project';

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function checkIfExists($text)
    {
        $project = Project::queryExec()
            ->where('name', '=', $text)
            ->first();

        if (!is_null($project)) {
            appGlobals::existsMessage(appGlobals::getProjectTableName(), $project->name, $project->id);
        }

        return $project;
    }
}
