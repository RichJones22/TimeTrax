<?php

namespace App\Http\Controllers;

use App\TimeCardHoursWorked;
use App\Http\Requests\PrepareTaskRequest;
use App\Task;
use App\Helpers\appGlobals;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param PrepareTaskRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(PrepareTaskRequest $request)
    {
        $taskRequestAttributes = $request->all();

        // if the hoursWorked has not been set, then just return to the client.  hoursWorked is calculated via .js
        // and does not seem to be populated before being sent to the server in some cases.  this has to do with
        // the return key being pressed prior to leaving the End Time field.
        if (!isset($taskRequestAttributes['hoursWorked'])) {
            return redirect()->back();
        }

        $task = new Task();

        $task->setStartTime($taskRequestAttributes['startt']);
        $task->setEndTime($taskRequestAttributes['endt']);
        $task->setHoursWorked($taskRequestAttributes['hoursWorked']);
        $task->setNotes($taskRequestAttributes['notes']);

        $task->setTaskTypeId($taskRequestAttributes['taskType']);
        $task->setTimeCardHoursWorkedId($taskRequestAttributes['time_card_hours_worked_id']);

        $task->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param $timeCardHoursWorkedId
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($timeCardHoursWorkedId)
    {
        // set appGlobal.clientId to current view, otherwise 'if (appGlobal.clientId)' in TimeCard.js causes a js load failure.
        appGlobals::populateJsGlobalClient();

        // used for routing.
        appGlobals::setSessionVariableAppGlobalTimeCardTableName($timeCardHoursWorkedId);

        // get all task for a specific time_card.date.
        /* @noinspection PhpUndefinedMethodInspection */
        $tasks = Task::where('time_card_hours_worked_id', '=', $timeCardHoursWorkedId)->get()->sortBy('start_time');

        // derive total hours worked.
        $totalHoursWorked = 0;
        foreach ($tasks as $task) {
            $totalHoursWorked += $task->hours_worked;
        }

        // eager load task_type for each task.
        /* @noinspection PhpUndefinedMethodInspection */
        $tasks->load('taskType');

        // get time_card data.
        /* @noinspection PhpUndefinedMethodInspection */
        $timeCard = TimeCardHoursWorked::where('id', '=', $timeCardHoursWorkedId)->get();

        // check if $timeCardHoursWorkedId exists, if not return 404 message.
        if (count($timeCard) == 0) {
            abort(404, 'Your Task ID does not exist.');
        }

        // pass the data to the view.
        return view('pages.userTaskView')
            ->with('tasks', $tasks)
            ->with('timeCard', $timeCard)
            ->with('timeCardHoursWorkedId', $timeCardHoursWorkedId)
            ->with('totalHoursWorked', $totalHoursWorked);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::destroy($id);

        return redirect()->back();
    }
}
