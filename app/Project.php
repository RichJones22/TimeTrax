<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
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
     * @return a record.
     */
    static public function checkIfExists($text) {
        $project = Project::where('name', '=', $text)->first();

        if (!is_null($project)) {
            existsMessage($project->table, $project->name, $project->id);
        }

        return $project;
    }



}
