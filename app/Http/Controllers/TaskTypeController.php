<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\PrepareTaskTypeRequest;
use \App\TaskType;
use \App\Helpers\appGlobals;

class TaskTypeController extends Controller
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
    public function create(PrepareTaskTypeRequest $request, $clientId)
    {
        $taskRequestAttributes = $request->all();

        $taskType = new TaskType();

        $taskType->type = $taskRequestAttributes['taskType'];
        $taskType->description = $taskRequestAttributes['description'];
        $taskType->client_id = $clientId;

        $taskType->save();

        // reset the appGlobals::getTaskTypeTableName()
        \Session::forget(appGlobals::getTaskTypeTableName());

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
    public function show($clientId)
    {
        // get all task for a specific time_card.date.
        $taskTypes = TaskType::where('client_id', '=', $clientId)->get();

        // pass the data to the view.
        return view('pages.userTaskTypeView')
            ->with('taskTypes', $taskTypes)
            ->with('clientId', $clientId);
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

        TaskType::destroy($id);

        // reset the appGlobals::getTaskTypeTableName()
        \Session::forget(appGlobals::getTaskTypeTableName());

        return redirect()->back();
    }
}