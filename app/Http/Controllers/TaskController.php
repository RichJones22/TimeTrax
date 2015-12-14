<?php

namespace App\Http\Controllers;

use App\TimeCard;
use Illuminate\Http\Request;

use \App\Http\Requests\PrepareTaskRequest;
use \App\Http\Controllers\Controller;
use \App\Task;

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
    public function create(PrepareTaskRequest $request, $id)
    {
        $taskRequestAttributes = $request->all();

        $taskAttributes = [
            'time_card_id' => $id,
            'task_type_id' => $taskRequestAttributes['taskType'],
            'start_time' => $taskRequestAttributes['startt'],
            'end_time' => $taskRequestAttributes['endt'],
            'hours_worked' => $taskRequestAttributes['hoursWorked'],
            'notes' => $taskRequestAttributes['notes'],
        ];

        Task::create($taskAttributes);

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
    public function show($id)
    {
        // create separate task id to be passed to view.
        $taskTypeId = $id;

        // get all task for a specific time_card.date.
        $tasks = Task::where('time_card_id', '=', $id)->get()->sortBy('start_time');

        // eager load task_type for each task.
        $tasks->load('taskType');

        // get time_card data.
        $timeCard = TimeCard::where('id', '=', $id)->get();

        // pass the data to the view.
        return view('pages.userTaskView')
            ->with('tasks', $tasks)
            ->with('timeCard', $timeCard)
            ->with('taskTypeId', $taskTypeId);
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
