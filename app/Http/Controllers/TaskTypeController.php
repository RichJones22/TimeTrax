<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Http\Requests\PrepareTaskTypeRequest;
use \App\TaskType;
use \App\Helpers\appGlobals;

class TaskTypeController extends Controller
{

    /*******************************************************************************************************************
     * main routines.
     ******************************************************************************************************************/

    /**
     * Show the form for creating a new and updating an existing resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(PrepareTaskTypeRequest $request)
    {
        $taskRequestAttributes = $request->all();

        $this->saveOrUpdate($taskRequestAttributes);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($clientId, $timeCardId = null)
    {
        // set appGlobal.clientId to current view, otherwise 'if (appGlobal.clientId)' in TimeCard.js causes js load failure.
        appGlobals::populateJsGlobalClient();

        // get all task for a specific time_card.date.
        $taskTypes = TaskType::where('client_id', '=', $clientId)->get();

        // correctly sets the back button if the $timeCardId has been passed, the back button is set, else not.
        if (is_null($timeCardId)) {
            \Session::forget(appGlobals::getTaskTableName());
        } else {
            \Session::set(appGlobals::getTaskTableName(), $timeCardId);
        }

        // pass the data to the view.
        return view('pages.userTaskTypeView')
            ->with('taskTypes', $taskTypes)
            ->with('clientId', $clientId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TaskType::destroy($id);

        return redirect()->back();
    }

    /*******************************************************************************************************************
     * supporting routines
     ******************************************************************************************************************/

    protected function saveOrUpdate($taskRequestAttributes) {

        // check if record exists
        $oldTaskTypeExists = TaskType::where('id', '=', $taskRequestAttributes['saveTaskType_id'])->first();
        if ($oldTaskTypeExists) {
            if ($this->dataChanged($taskRequestAttributes, $oldTaskTypeExists)) {
                // interesting note:  for some reason eloquent wants me to use the old record when updating.  If I use
                // $newTaskType the ->update() method does not work and does not fail?
                $oldTaskTypeExists->id = $taskRequestAttributes['saveTaskType_id'];
                $oldTaskTypeExists->type = $taskRequestAttributes['taskType'];
                $oldTaskTypeExists->description = $taskRequestAttributes['description'];
                $oldTaskTypeExists->client_id = $taskRequestAttributes['client_id'];

                $oldTaskTypeExists->update();
            } else {
                return; // no change; just route back to the caller.
            }
        } else {
            $newTaskType = new TaskType();

            $newTaskType->type = $taskRequestAttributes['taskType'];
            $newTaskType->description = $taskRequestAttributes['description'];
            $newTaskType->client_id = $taskRequestAttributes['client_id'];

            $newTaskType->save();
        }
    }

    protected function dataChanged($taskRequestAttributes, $old) {
        if ($taskRequestAttributes['taskType'] == $old->type &&
            $taskRequestAttributes['description'] == $old->description &&
            $taskRequestAttributes['client_id'] == $old->client_id) {
            return false;
        }

        return true;
    }
}
