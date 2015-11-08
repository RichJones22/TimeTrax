<?php
namespace App\Observers;
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/8/15
 * Time: 11:49 AM
 */

class ProjectObserver
{
    /**
     * post successful call to the save() method
     * @param $project
     */
    public function created($project) {
        createdMessage(projectTableName(), $project->name ,$project->id);
    }
}