<?php

use \App\Client;
use \App\Project;
use \App\WorkType;
use \App\TimeCardFormat;

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
        $timeCardFormat->dow_01 = "SAT";
        $timeCardFormat->dow_02 = "MON";
        $timeCardFormat->dow_03 = "TUE";
        $timeCardFormat->dow_04 = "WED";
        $timeCardFormat->dow_05 = "THU";
        $timeCardFormat->dow_06 = "FRI";
        $timeCardFormat->dow_07 = "SUN";

        $timeCardFormat->client_id = $client->id;

        $timeCardFormat->save();
    }
});