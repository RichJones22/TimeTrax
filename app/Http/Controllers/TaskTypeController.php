<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use \App\Http\Requests\PrepareTaskTypeRequest;
use \App\TaskType;
use \App\Helpers\appGlobals;

/**
 * Class TaskTypeController
 * @package App\Http\Controllers
 */
class TaskTypeController extends Controller
{
    /**
     * @param $ttvAttributes
     */
    public function create($ttvAttributes)
    {
        $newTaskType = new TaskType();

        $newTaskType->type = $ttvAttributes['type'];
        $newTaskType->description = $ttvAttributes['description'];
        $newTaskType->client_id = $ttvAttributes['client_id'];

        $newTaskType->save();
    }

    /**
     * @param $clientId
     * @param null $timeCardId
     * @return mixed
     */
    public function show($clientId, $timeCardId = null)
    {
        // set appGlobals.
        $this->setGlobals($timeCardId);

        // get all task for a specific time_card.date.
        $taskTypes = TaskType::where('client_id', $clientId)->get();

        // pass the data to the view.
        return view('pages.userTaskTypeView')
            ->with('taskTypes', $taskTypes)
            ->with('clientId', $clientId);
    }

    /**
     * ajax called method
     */
    public function update()
    {
        $taskType = new TaskType();

        $taskType->updateRec(json_decode($_GET['data']));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        TaskType::destroy($id);

        return redirect()->back();
    }

    /**
     * @param $timeCardId
     */
    private function setGlobals($timeCardId)
    {
        // set appGlobal.clientId to current view, otherwise 'if (appGlobal.clientId)' in TimeCard.js causes js load failure.
        appGlobals::populateJsGlobalClient();

        // set appGlobal.taskTypeUpdateURI for ajax update routing.
        appGlobals::populateJsGlobalTaskTypeUpdateURI();

        // set appGlobal.ttvTypeClearText when phpunit selenium testing, only.
        appGlobals::populateJsGlobalTtvTypeClearTextTrue();

        // correctly sets the back button if the $timeCardId has been passed, the back button is set, else not.
        if (is_null($timeCardId)) {
            \Session::forget(appGlobals::getTaskTableName());
        } else {
            \Session::set(appGlobals::getTaskTableName(), $timeCardId);
        }
    }
}
