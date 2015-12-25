<?php

namespace App\Http\Controllers;

use App\TimeCard;
use Illuminate\Http\Request;

use \App\Http\Requests\PrepareTaskRequest;
use \App\Http\Controllers\Controller;
use \App\Task;

use DB;

class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(PrepareTaskRequest $request, $timeCardId)
    {
        $taskRequestAttributes = $request->all();

        $task = new Task();

        $task->start_time = $taskRequestAttributes['startt'];
        $task->end_time = $taskRequestAttributes['endt'];
        $task->hours_worked = $taskRequestAttributes['hoursWorked'];
        $task->notes = $taskRequestAttributes['notes'];

        $task->task_type_id = $taskRequestAttributes['taskType'];
        $task->time_card_id = $timeCardId;

//        try {
//            DB::beginTransaction();
//            $result = $task->save();
//
//            dd($result);
//
//            DB::commit();
//        } catch (Exception $e) {
//            dd("*********** save failed. ************");
//            DB::rollBack();
//            //session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));
//        }

        $task->save();

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($timeCardId)
    {
        // create separate task id to be passed to view.
//        $timeCardId = $id;

        // get all task for a specific time_card.date.
        $tasks = Task::where('time_card_id', '=', $timeCardId)->get()->sortBy('start_time');

        // derive total hours worked.
        $totalHoursWorked=0;
        foreach($tasks as $task) {
            $totalHoursWorked += $task->hours_worked;
        }

        // eager load task_type for each task.
        $tasks->load('taskType');

        // get time_card data.
        $timeCard = TimeCard::where('id', '=', $timeCardId)->get();

        // pass the data to the view.
        return view('pages.userTaskView')
            ->with('tasks', $tasks)
            ->with('timeCard', $timeCard)
            ->with('timeCardId', $timeCardId)
            ->with('totalHoursWorked', $totalHoursWorked);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Task::destroy($id);

        return redirect()->back();
    }
}
