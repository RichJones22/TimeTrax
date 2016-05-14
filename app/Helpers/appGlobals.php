<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/9/15
 * Time: 4:41 PM
 */

namespace app\Helpers;
use \Illuminate\Database\QueryException;

use \App\Client;
use \App\Project;
use \App\TimeCardFormat;
use \App\WorkType;
use \App\Work;
use \App\TimeCard;
use \App\TimeCardHoursWorked;
use \App\TaskType;
use \App\Task;
use \App\TestingSeleniumVariables;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class appGlobals
{
    const DAYS_IN_WEEK_NUM = 7;
    const TBL_TASK_START_TIME_GT_END_TIME = '45001';
    const TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD = 45002;
    const TBL_TASK_TYPE_TYPE_ALREADY_EXISTS = 45003;
    const TBL_TASK_TYPE_CONSTRAINT_VIOLATION = 45004;


    // info message numbers.
    const INFO_TIME_VALUE_OVERLAP = 1000;

    static protected $clientTableName;
    static protected $projectTableName;
    static protected $workTypeTableName;
    static protected $timeCardFormatTableName;
    static protected $workTableName;
    static protected $timeCardTableName;
    static protected $timeCardHoursWorkedTableName;
    static protected $timeTaskTypeTableName;
    static protected $timeTaskTableName;
    static protected $testingSeleniumVariablesTableName;

    // routes used by both javascript and php
    // static protected $domain="http://timetrax.dev/";
    static protected $timeCardURI="timeCard/";
    static protected $workURI="work/";
    static protected $taskTypeUpdateURI="taskType/update/";

    static protected $infoMessageType = 'info_message';
    static protected $messageText = [
        // app info messages
        self::INFO_TIME_VALUE_OVERLAP => 'One of your entered time values overlaps with existing data.  Your data has been refreshed.',
        self::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD => 'Type restricted to one word.',
        self::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS => 'Type already exists.  Your data has been refreshed.',
        self::TBL_TASK_TYPE_CONSTRAINT_VIOLATION => "Type (%s) currently exists on tasks.  Can't delete.",
    ];

    //used for testing Relational Database Management System fail cases.
    // - set to true for the RDBMS test files.
    static protected $testRDBMS = false;

    public function __construct() {
        self::$clientTableName = with(new Client)->getTable();
        self::$projectTableName = with(new Project)->getTable();
        self::$workTypeTableName = with(new WorkType)->getTable();
        self::$timeCardFormatTableName = with(new TimeCardFormat)->getTable();
        self::$workTableName = with(new Work)->getTable();
        self::$timeCardTableName = with(new TimeCard)->getTable();
        self::$timeCardHoursWorkedTableName = with(new TimeCardHoursWorked)->getTable();
        self::$timeTaskTypeTableName = with(new TaskType)->getTable();
        self::$timeTaskTableName = with(new Task)->getTable();
        self::$testingSeleniumVariablesTableName = with(new TestingSeleniumVariables)->getTable();
    }

    static public function getClientTableName() {
        return self::$clientTableName;
    }

    static public function getProjectTableName() {
        return self::$projectTableName;
    }

    static public function getWorkTypeTableName() {
        return self::$workTypeTableName;
    }

    static public function getTimeCardFormatTableName() {
        return self::$timeCardFormatTableName;
    }

    static public function getWorkTableName() {
        return self::$workTableName;
    }

    static public function getTimeCardTableName() {
        return self::$timeCardTableName;
    }

    static public function getTimeCardHoursWorkedTableName() {
        return self::$timeCardHoursWorkedTableName;
    }

    static public function getTaskTypeTableName() {
        return self::$timeTaskTypeTableName;
    }

    static public function getTaskTableName() {
        return self::$timeTaskTableName;
    }

    static public function getTestingSeleniumVariablesTableName() {
        return self::$testingSeleniumVariablesTableName;
    }

    static public function existsMessage($table, $text, $key) {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' exists.<br>';
    }

    static public function createdMessage($table, $text, $key) {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' created.<br>';
    }

    static public function reportError(QueryException $e, $file, $line) {
        echo "error code ". $e->getCode() . " found at line: " . $line . "<br>";
        echo "in file: " . $file . "<br>";
        echo "with message: ". "<br>";
        echo $e->getMessage();
        //var_dump($e);
    }

    static public function getInfoMessageType() {
        return self::$infoMessageType;
    }

    static public function getInfoMessageText($messageNum) {
        if (array_key_exists($messageNum, self::$messageText)) {
            return self::$messageText[$messageNum];
        } else {
            return "error message not found for error number -- $messageNum";
        }
    }

    static public function setTestRDBMS($value) {
        self::$testRDBMS = $value;
    }

    static public function getTestRDBMS() {

        $result = Schema::hasTable('testing_selenium_variables');

        if ($result) {
            $result = \DB::table('testing_selenium_variables')->select('testingRDBMS')->where('id', 1)->first();

            if ($result) {
                return $result->testingRDBMS;
            }
        }

        return false;
    }

    static public function getDomain() {
        return self::$domain;
    }

    static public function getTimeCardURI() {
        return self::$timeCardURI;
    }

    static public function getWorkURI() {
        return self::$workURI;
    }

    static public function getTaskTypeUpdateURI() {
        return self::$taskTypeUpdateURI;
    }

    static public function getBeginningOfCurrentWeek($dateRange) {
        if (substr($dateRange,0,1) == '(') {
            $dateSelected = substr($dateRange,2,10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->AddDays(1); // one extra day to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    static public function getBeginningOfNextWeek($dateRange) {
        if (substr($dateRange,0,1) == '(') {
            $dateSelected = substr($dateRange,2,10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->AddDays(8); // extra days to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    static public function getBeginningOfPreviousWeek($dateRange) {
        if (substr($dateRange,0,1) == '(') {
            $dateSelected = substr($dateRange,2,10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->SubDays(6); // less day to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    static public function getClientIdOfProjectRecordingTimeFor() {
        $data = \DB::table('project')->where('flag_recording_time_for', 1)->first();

        return $data->client_id;
    }

    static public function populateJsGlobalSpace($request) {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'timeCardURI'       => $request->root() . "/" . appGlobals::getTimeCardURI(),
            'workURI'           => appGlobals::getWorkURI()
        ]);

        self::populateJsGlobalClient();
    }

    static public function populateJsGlobalClient() {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'clientId'    => appGlobals::getClientIdOfProjectRecordingTimeFor()
        ]);
    }

    static public function populateJsGlobalTaskTypeUpdateURI() {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'taskTypeUpdateURI' => appGlobals::getTaskTypeUpdateURI()
        ]);
    }

    static public function getIsoBeginningDowDate($timeCardHoursWorkedId) {
        $data = \DB::table('time_card_hours_worked')
            ->join('time_card', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->where('time_card_hours_worked.id', $timeCardHoursWorkedId)
            ->select('time_card.iso_beginning_dow_date')
            ->first();

        return $data->iso_beginning_dow_date;
    }

    /**
     * @param $timeCardHoursWorkedId
     */
    static public function setSessionVariableAppGlobalTimeCardTableName($timeCardHoursWorkedId)
    {
        if (is_null($timeCardHoursWorkedId)) {
            \Session::forget(appGlobals::getTimeCardTableName());
        } else {
            \Session::set(appGlobals::getTimeCardTableName(), $timeCardHoursWorkedId);
        }
    }

}

global $appGlobals;
$appGlobals = new appGlobals();
