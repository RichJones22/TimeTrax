<?php

use \App\Helpers\appGlobals;
use \Illuminate\Database\QueryException;

use \App\Client;
use \App\Project;
use \App\WorkType;
use \App\TimeCardFormat;
use \App\Work;
use \App\TimeCard;
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
    $description = 'This thing does not work right.';
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

    /*******************************************************************************************************************
     * time_card insert(s)
     ******************************************************************************************************************/
    $date = '2015-11-12';
    if (is_null($timeCard = TimeCard::checkIfExists($date))) {

        // get $work->id
        $work = Work::where('work_type_description', '=', 'This thing does not work right.')->first();

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

        $timeCard->date_worked = $date;
        $timeCard->dow = "THU";
        $timeCard->total_hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-13';
    if (is_null($timeCard = TimeCard::checkIfExists($date))) {

        // get $work->id
        $work = Work::where('work_type_description', '=', 'This thing does not work right.')->first();

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

        $timeCard->date_worked = $date;
        $timeCard->dow = "FRI";
        $timeCard->total_hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-16';
    if (is_null($timeCard = TimeCard::checkIfExists($date))) {

        // get $work->id
        $work = Work::where('work_type_description', '=', 'This thing does not work right.')->first();

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

        $timeCard->date_worked = $date;
        $timeCard->dow = "MON";
        $timeCard->total_hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-17';
    if (is_null($timeCard = TimeCard::checkIfExists($date))) {

        // get $work->id
        $work = Work::where('work_type_description', '=', 'This thing does not work right.')->first();

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

        $timeCard->date_worked = $date;
        $timeCard->dow = "TUE";
        $timeCard->total_hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    $date = '2015-11-18';
    if (is_null($timeCard = TimeCard::checkIfExists($date))) {

        // get $work->id
        $work = Work::where('work_type_description', '=', 'This thing does not work right.')->first();

        // get $timeCardFormat->id
        $timeCardFormat = TimeCardFormat::where('description', '=', 'Day of week starts on SAT and ends on SUN')->first();

        $timeCard = new TimeCard;

        $timeCard->date_worked = $date;
        $timeCard->dow = "WED";
        $timeCard->total_hours_worked = 8.0;

        $timeCard->time_card_format_id = $timeCardFormat->id;
        $timeCard->work_id = $work->id;

        $timeCard->save();
    }

    /*******************************************************************************************************************
     * task_type insert(s)
     ******************************************************************************************************************/
    $type = 'Code';
    $description = 'Type for coding tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;

        $taskType->save();
    }

    $type = 'Test';
    $description = 'Type for testing tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;

        $taskType->save();
    }

    $type = 'Deployment';
    $description = 'Type for deployment tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;

        $taskType->save();
    }

    $type = 'Analysis';
    $description = 'Type for Analyzing tasks';
    if (is_null($taskType = TaskType::checkIfExists($type))) {

        $taskType = new TaskType;

        $taskType->type = $type;
        $taskType->description = $description;

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

Route::get('get_all_tasks', function() {

    $sessionData = Session::get(appGlobals::getTaskTypeTableName());

    if (!$sessionData) {
        echo "task session data was reset..." . "<br>";

        $tasks = TaskType::all();

        foreach($tasks as $task) {
            $data[] = ['id' => $task->id, 'text' => $task->type];
        }

        Session::set(appGlobals::getTaskTypeTableName(), $data);

        $sessionData = Session::get(appGlobals::getTaskTypeTableName());
    }

    return $sessionData;

});

