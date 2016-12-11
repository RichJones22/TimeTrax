<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\appGlobals;
use App\Http\Requests\PrepareTaskRequest;
use App\Task;
use App\TimeCardHoursWorked;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(PrepareTaskRequest $request)
    {
        $taskRequestAttributes = $request->all();

        // if the hoursWorked has not been set, then just return to the client.  hoursWorked is calculated via .js
        // and does not seem to be populated before being sent to the server in some cases.  this has to do with
        // the return key being pressed prior to leaving the End Time field.
        if ( ! isset($taskRequestAttributes['hoursWorked'])) {
            return redirect()->back();
        }

        $task = new Task();

        $task->start_time = $taskRequestAttributes['startt'];
        $task->end_time = $taskRequestAttributes['endt'];
        $task->hours_worked = $taskRequestAttributes['hoursWorked'];
        $task->notes = $taskRequestAttributes['notes'];

        $task->task_type_id = $taskRequestAttributes['taskType'];
        $task->time_card_hours_worked_id = $taskRequestAttributes['time_card_hours_worked_id'];

        $task->save();

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * @param $timeCardHoursWorkedId
     * @return View
     */
    public function show($timeCardHoursWorkedId)
    {
        // set appGlobal.clientId to current view, otherwise 'if (appGlobal.clientId)' in TimeCard.js causes a js load failure.
        appGlobals::populateJsGlobalClient();

        // used for routing.
        appGlobals::setSessionVariableAppGlobalTimeCardTableName($timeCardHoursWorkedId);

        // get all task for a specific time_card.date.
        $tasks = Task::where('time_card_hours_worked_id', '=', $timeCardHoursWorkedId)->get()->sortBy('start_time');

        // derive total hours worked.
        $totalHoursWorked = 0;
        foreach ($tasks as $task) {
            $totalHoursWorked += $task->hours_worked;
        }

        // eager load task_type for each task.
        $tasks->load('taskType');

        // get time_card data.
        $timeCard = TimeCardHoursWorked::where('id', '=', $timeCardHoursWorkedId)->get();

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
