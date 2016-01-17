<?php

use \App\Helpers\appGlobals;
use \Illuminate\Database\QueryException;

use \App\Client;
use \App\Project;
use \App\WorkType;
use \App\TimeCardFormat;
use \App\Work;
use \App\TimeCard;
use \App\TimeCardHoursWorked;
use \App\TaskType;
use \App\Task;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/***********************************************************************************************************************
 * helpers
 **********************************************************************************************************************/


/***********************************************************************************************************************
 * routes
 **********************************************************************************************************************/
Route::get('/', function () {
    return view('welcome');
});

// route to task view; show a specific task.
Route::get('task/{task}'        , ['as' => 'task.show', 'uses' => 'TaskController@show']);

// insert a task
Route::post('task/create/'      , ['as' => 'task.create', 'uses' => 'TaskController@create']);

// delete a task
Route::post('task/{task}'       , ['as' => 'task.destroy', 'uses' => 'TaskController@destroy']);

Route::get('get_all_tasks', function() {

    $tasks = TaskType::all();

    foreach($tasks as $task) {
        $data[] = ['id' => $task->id, 'type' => $task->type];
    }

    return $data;

});



// route to taskType view.
Route::get('taskType/{taskType}'            , ['as' => 'taskType.show', 'uses' => 'TaskTypeController@show']);
Route::get('taskType/{taskType}/task/{task}', ['as' => 'taskType.task.show', 'uses' => 'TaskTypeController@show']);

// insert a task type.
Route::post('taskType/create/'              , ['as' => 'taskType.create', 'uses' => 'TaskTypeController@create']);

// delete a task type.
Route::post('taskType/destroy/{taskType}'   , ['as' => 'taskType.destroy', 'uses' => 'TaskTypeController@destroy']);



// route to timeCard type view.
Route::get(appGlobals::getTimeCardURI() . '{dateSelected?}', ['as' => 'timeCard.show', 'uses' => 'TimeCardController@show']);

// insert a TimeCard record.
Route::post(appGlobals::getTimeCardURI() . 'create/{id}', ['as' => 'timeCard.create', 'uses' => 'TimeCardController@create']);

// delete a TimeCard record.
Route::post(appGlobals::getTimeCardURI() . 'destroy/{id}', ['as' => 'timeCard.destroy', 'uses' => 'TimeCardController@destroy']);

Route::get(appGlobals::getTimeCardURI() . appGlobals::getWorkURI() . '{clientId}', function($client_id) {

    $data = \DB::table('project')->where('project.client_id', $client_id)
        ->join('work', 'project.id', '=', 'work.project_id')
        ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
        ->select('work_type.id', 'work_type.type', 'work.work_type_description')
        ->orderby('work.work_type_id')
        ->get();

    return $data;
});



//Route::resource('zzbob', 'TimeCardController');



/**
 * creating records
 */
Route::get('create_data', function() {

    /*******************************************************************************************************************
    * client insert(s)
    *******************************************************************************************************************/
    if (is_null($client = Client::checkIfExists('Kendra Scott'))) {

        $client = new Client;

        $client->name = 'Kendra Scott';

        $client->save();
    }

    /*******************************************************************************************************************
     * project insert(s)
     ******************************************************************************************************************/
    if (is_null($project = Project::checkIfExists('Magento Development'))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $project = new Project;

        $project->name = 'Magento Development';
        $project->client_id = $client->id;
        $project->flag_recording_time_for = true;

        $project->save();
    }

    /*******************************************************************************************************************
     * work_type insert(s)
     * - Defect
     * - Feature
     * - Atlassian Ticket
     ******************************************************************************************************************/
    $type = 'Atlassian Ticket';
    if (is_null($project = WorkType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $workType = new Worktype;

        $workType->type = $type;
        $workType->client_id = $client->id;

        $workType->save();
    }

    $type = 'Defect';
    if (is_null($project = WorkType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $workType = new Worktype;

        $workType->type = $type;
        $workType->client_id = $client->id;

        $workType->save();
    }

    $type = 'Feature';
    if (is_null($project = WorkType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $workType = new Worktype;

        $workType->type = $type;
        $workType->client_id = $client->id;

        $workType->save();
    }

    /*******************************************************************************************************************
     * time_card_format insert(s)
     ******************************************************************************************************************/
    $description = 'Day of week starts on SAT and ends on SUN';
    if (is_null($timeCardFormat = TimeCardFormat::checkIfExists($description))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $timeCardFormat = new Timecardformat;

        $timeCardFormat->description = $description;
        $timeCardFormat->dow_00 = "SUN";
        $timeCardFormat->dow_01 = "MON";
        $timeCardFormat->dow_02 = "TUE";
        $timeCardFormat->dow_03 = "WED";
        $timeCardFormat->dow_04 = "THU";
        $timeCardFormat->dow_05 = "FRI";
        $timeCardFormat->dow_06 = "SAT";

        $timeCardFormat->client_id = $client->id;

        $timeCardFormat->save();
    }

    /*******************************************************************************************************************
     * work insert(s)
     ******************************************************************************************************************/
    $description = 'The catalog view is performing too slowly.';
    if (is_null($work = Work::checkIfExists($description))) {

        // get $project->id
        $project = Project::where('name', '=', 'Magento Development')->first();

        // get $project->id
        $workType = WorkType::where('type', '=', 'Defect')->first();

        $work = new Work;

        $work->work_type_description = $description;

        $work->project_id = $project->id;
        $work->work_type_id = $workType->id;

        $work->save();
    }

    $description = 'The Task table needs three additional columns.';
    if (is_null($work = Work::checkIfExists($description))) {

        // get $project->id
        $project = Project::where('name', '=', 'Magento Development')->first();

        // get $project->id
        $workType = WorkType::where('type', '=', 'Atlassian Ticket')->first();

        $work = new Work;

        $work->work_type_description = $description;

        $work->project_id = $project->id;
        $work->work_type_id = $workType->id;

        $work->save();
    }

    $description = 'A new landing page is required to support Fall 2016 GNO.';
    if (is_null($work = Work::checkIfExists($description))) {

        // get $project->id
        $project = Project::where('name', '=', 'Magento Development')->first();

        // get $project->id
        $workType = WorkType::where('type', '=', 'Feature')->first();

        $work = new Work;

        $work->work_type_description = $description;

        $work->project_id = $project->id;
        $work->work_type_id = $workType->id;

        $work->save();
    }

    /*******************************************************************************************************************
     * time_card insert(s)
     ******************************************************************************************************************/
    $date = '2015-11-12';

    // get $work->id
    $work = Work::where('work_type_description', '=', 'The catalog view is performing too slowly.')->first();
    if (is_null($timeCard = TimeCard::checkIfExists($work))) {

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

//        $timeCard->date_worked = $date;
//        $timeCard->dow = "THU";
//        $timeCard->hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-13';

    // get $work->id
    $work = Work::where('work_type_description', '=', 'The Task table needs three additional columns.')->first();
    if (is_null($timeCard = TimeCard::checkIfExists($work))) {

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

//        $timeCard->date_worked = $date;
//        $timeCard->dow = "FRI";
//        $timeCard->hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-16';


    // get $work->id
    $work = Work::where('work_type_description', '=', 'A new landing page is required to support Fall 2016 GNO.')->first();
    if (is_null($timeCard = TimeCard::checkIfExists($work))) {

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

//        $timeCard->date_worked = $date;
//        $timeCard->dow = "MON";
//        $timeCard->hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    /*******************************************************************************************************************
     * time_card_hours_worked insert(s)
     ******************************************************************************************************************/

    $date = '2015-11-12';

    // get $work->id
    $work = Work::where('work_type_description', '=', 'The catalog view is performing too slowly.')->first();
    if (is_null($timeCardHoursWorked = TimeCardHoursWorked::checkIfExists($work))) {

        $timeCardHoursWorked = new TimeCardHoursWorked;

        $timeCardHoursWorked->date_worked = $date;
        $timeCardHoursWorked->dow = "THU";
        $timeCardHoursWorked->hours_worked = 8.0;

        $timeCardHoursWorked->time_card_id = $work->id;

        $timeCardHoursWorked->save();

    }

    $date = '2015-11-13';

    // get $work->id
    $work = Work::where('work_type_description', '=', 'The Task table needs three additional columns.')->first();
    if (is_null($timeCardHoursWorked = TimeCardHoursWorked::checkIfExists($work))) {

        $timeCardHoursWorked = new TimeCardHoursWorked;

        $timeCardHoursWorked->date_worked = $date;
        $timeCardHoursWorked->dow = "FRI";
        $timeCardHoursWorked->hours_worked = 8.0;

        $timeCardHoursWorked->time_card_id = $work->id;

        $timeCardHoursWorked->save();

    }


    $date = '2015-11-16';

    // get $work->id
    $work = Work::where('work_type_description', '=', 'A new landing page is required to support Fall 2016 GNO.')->first();
    if (is_null($timeCardHoursWorked = TimeCardHoursWorked::checkIfExists($work))) {

        $timeCardHoursWorked = new TimeCardHoursWorked;

        $timeCardHoursWorked->date_worked = $date;
        $timeCardHoursWorked->dow = "MON";
        $timeCardHoursWorked->hours_worked = 8.0;

        $timeCardHoursWorked->time_card_id = $work->id;

        $timeCardHoursWorked->save();

    }


    /*******************************************************************************************************************
     * task_type insert(s)
     ******************************************************************************************************************/
    $type = 'Code';
    $description = 'Type for coding tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;
        $taskType->client_id = $client->id;

        $taskType->save();
    }

    $type = 'Test';
    $description = 'Type for testing tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;
        $taskType->client_id = $client->id;

        $taskType->save();
    }

    $type = 'Deployment';
    $description = 'Type for deployment tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;
        $taskType->client_id = $client->id;

        $taskType->save();
    }

    $type = 'Analysis';
    $description = 'Type for Analyzing tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;
        $taskType->client_id = $client->id;

        $taskType->save();
    }

    /*******************************************************************************************************************
     * task insert(s)
     ******************************************************************************************************************/
    $startTime = '07:00:00';
    $endTime = '11:30:00';
    $hoursWorked = 3.5;
    $notes = "worked defect number 127068";
    if (is_null($task = Task::checkIfExists($startTime))) {

        // get $taskType->id
        $taskType = TaskType::where('type', '=', 'Code')->first();

        // get $timeCard->id
        $timeHoursWorkedCard = TimeCardHoursWorked::where('date_worked', '=', '2015-11-12')->first();

        $task = new Task;

        $task->start_time = $startTime;
        $task->end_time = $endTime;
        $task->hours_worked = $hoursWorked;
        $task->notes = $notes;

        $task->task_type_id = $taskType->id;
        $task->time_card_hours_worked_id = $timeHoursWorkedCard->id;

        $task->save();
    }

    $startTime = '12:00:00';
    $endTime = '17:00:00';
    $hoursWorked = 5.00;
    $notes = "worked defect number 127068";
    if (is_null($task = Task::checkIfExists($startTime))) {

        // get $taskType->id
        $taskType = TaskType::where('type', '=', 'Code')->first();

        // get $timeCard->id
        $timeHoursWorkedCard = TimeCardHoursWorked::where('date_worked', '=', '2015-11-12')->first();

        $task = new Task;

        $task->start_time = $startTime;
        $task->end_time = $endTime;
        $task->hours_worked = $hoursWorked;
        $task->notes = $notes;

        $task->task_type_id = $taskType->id;
        $task->time_card_hours_worked_id = $timeHoursWorkedCard->id;

        $task->save();
    }
});

Route::get('delete_task_data', function() {
    DB::table('task')->truncate();

    echo "task data deleted!";
});

Route::get('delete_taskType_data', function() {
    DB::table('task')->delete();
    DB::table('task_type')->delete();

    echo "task data deleted!";
});

Route::get('add_task_data_firstPass', function() {
    $startTime = '07:00:00';
    $endTime = '11:30:00';
    $hoursWorked = 3.5;
    $notes = "worked defect number 127068";
    if (is_null($task = Task::checkIfExists($startTime))) {

        // get $taskType->id
        $taskType = TaskType::where('type', '=', 'Code')->first();

        // get $timeCard->id
        $timeCard = TimeCard::where('date_worked', '=', '2015-11-12')->first();

        $task = new Task;

        $task->start_time = $startTime;
        $task->end_time = $endTime;
        $task->hours_worked = $hoursWorked;
        $task->notes = $notes;

        $task->task_type_id = $taskType->id;
        $task->time_card_id = $timeCard->id;

        $task->save();
    }

    $startTime = '12:00:00';
    $endTime = '17:00:00';
    $hoursWorked = 5.00;
    $notes = "worked defect number 127068";
    if (is_null($task = Task::checkIfExists($startTime))) {

        // get $taskType->id
        $taskType = TaskType::where('type', '=', 'Code')->first();

        // get $timeCard->id
        $timeCard = TimeCard::where('date_worked', '=', '2015-11-12')->first();

        $task = new Task;

        $task->start_time = $startTime;
        $task->end_time = $endTime;
        $task->hours_worked = $hoursWorked;
        $task->notes = $notes;

        $task->task_type_id = $taskType->id;
        $task->time_card_id = $timeCard->id;

        $task->save();
    }
});

Route::get('add_taskType_data', function() {
    $type = 'Lunch';
    $description = 'Lunch break';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        // get $client->id
        $client = Client::where('name', '=', 'Kendra Scott')->first();

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;
        $taskType->client_id = $client->id;

        $taskType->save();
    }
});

/**
 * prototyping...
 */
Route::get('play', function() {

    $myArray['SUN'] = 0.0;
    $myArray['MON'] = 0.0;
    $myArray['TUE'] = 5.0;
    $myArray['WED'] = 0.0;
    $myArray['THU'] = 3.0;
    $myArray['FRI'] = 2.0;
    $myArray['SAT'] = 10.0;

    $description = 'Day of week starts on SAT and ends on SUN';
    $timeCardFormat = TimeCardFormat::where('description', '=', $description)->first();

    for ($j = 0; $j < appGlobals::DAYS_IN_WEEK_NUM; $j++) {
        $pos = $timeCardFormat->{"dow_0" . $j};
        echo "For $pos the hours worked are: " . $myArray[$pos]  . "<br>";
    }

});

Route::get('task_fail', function() {

    $startTime = '17:00:00';
    $endTime = '12:00:00';
    $hoursWorked = 5.00;
    $notes = "error testing";
    if (is_null($task = Task::checkIfExists($startTime))) {

        // get $taskType->id
        $taskType = TaskType::where('type', '=', 'Code')->first();

        // get $timeCard->id
        $timeCard = TimeCard::where('date_worked', '=', '2015-11-12')->first();

        $task = new Task;

        $task->start_time = $startTime;
        $task->end_time = $endTime;
        $task->hours_worked = $hoursWorked;
        $task->notes = $notes;

        $task->task_type_id = $taskType->id;
        $task->time_card_id = $timeCard->id;

        try {
            $task->save();
        } catch (QueryException $e) {
            if ($e->getCode() == appGlobals::TBL_TASK_START_TIME_GT_END_TIME) {
                appGlobals::reportError($e, __FILE__, __LINE__);
            }
        }
    }
});

Route::get('get_all_clients', function() {

    $sessionData = Session::get('clients');

    if (!$sessionData) {
        echo "client session data was reset..." . "<br>";

        $clients = Client::all();

        foreach($clients as $client) {
            $data[] = ['id' => $client->id, 'text' => $client->name];
        }

        Session::set('clients', $data);

        $sessionData = Session::get('clients');
    }

    return $sessionData;

});

Route::get('unset_all_clients', function() {

    Session::forget('clients');

});



