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
    static protected $taskTypeURI="taskType/";
    static protected $update="/update/";

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

    public function __construct()
    {
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

    public static function getClientTableName()
    {
        return self::$clientTableName;
    }

    public static function getProjectTableName()
    {
        return self::$projectTableName;
    }

    public static function getWorkTypeTableName()
    {
        return self::$workTypeTableName;
    }

    public static function getTimeCardFormatTableName()
    {
        return self::$timeCardFormatTableName;
    }

    public static function getWorkTableName()
    {
        return self::$workTableName;
    }

    public static function getTimeCardTableName()
    {
        return self::$timeCardTableName;
    }

    public static function getTimeCardHoursWorkedTableName()
    {
        return self::$timeCardHoursWorkedTableName;
    }

    public static function getTaskTypeTableName()
    {
        return self::$timeTaskTypeTableName;
    }

    public static function getTaskTableName()
    {
        return self::$timeTaskTableName;
    }

    public static function getTestingSeleniumVariablesTableName()
    {
        return self::$testingSeleniumVariablesTableName;
    }

    public static function existsMessage($table, $text, $key)
    {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' exists.<br>';
    }

    public static function createdMessage($table, $text, $key)
    {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' created.<br>';
    }

    public static function reportError(QueryException $e, $file, $line)
    {
        echo "error code ". $e->getCode() . " found at line: " . $line . "<br>";
        echo "in file: " . $file . "<br>";
        echo "with message: ". "<br>";
        echo $e->getMessage();
        //var_dump($e);
    }

    public static function getInfoMessageType()
    {
        return self::$infoMessageType;
    }

    public static function getInfoMessageText($messageNum)
    {
        if (array_key_exists($messageNum, self::$messageText)) {
            return self::$messageText[$messageNum];
        } else {
            return "error message not found for error number -- $messageNum";
        }
    }

    public static function setTestRDBMS($value)
    {
        self::$testRDBMS = $value;
    }

    public static function getTestRDBMS()
    {

        $result = Schema::hasTable('testing_selenium_variables');

        if ($result) {
            $result = \DB::table('testing_selenium_variables')->select('testingRDBMS')->where('id', 1)->first();

            if ($result) {
                return $result->testingRDBMS;
            }
        }

        return false;
    }

    public static function getDomain()
    {
        return self::$domain;
    }

    public static function getTimeCardURI()
    {
        return self::$timeCardURI;
    }

    public static function getWorkURI()
    {
        return self::$workURI;
    }

    public static function getTaskTypeUpdateURI()
    {
        return self::$taskTypeUpdateURI;
    }

    public static function getBeginningOfCurrentWeek($dateRange)
    {
        if (substr($dateRange, 0, 1) == '(') {
            $dateSelected = substr($dateRange, 2, 10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->AddDays(1); // one extra day to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    public static function getBeginningOfNextWeek($dateRange)
    {
        if (substr($dateRange, 0, 1) == '(') {
            $dateSelected = substr($dateRange, 2, 10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->AddDays(8); // extra days to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    public static function getBeginningOfPreviousWeek($dateRange)
    {
        if (substr($dateRange, 0, 1) == '(') {
            $dateSelected = substr($dateRange, 2, 10);
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
            $dateRange = $dateSelected->SubDays(6); // less day to accommodate for iso standard of a Monday start to the week
                                                    // this will push it into the next week.
            return $dateRange->toDateString();
        }
        return $dateRange;
    }

    public static function getClientIdOfProjectRecordingTimeFor()
    {
        $data = \DB::table('project')->where('flag_recording_time_for', 1)->first();

        return $data->client_id;
    }

    public static function populateJsGlobalSpace()
    {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'timeCardURI'       => url("/") . "/" . appGlobals::getTimeCardURI(),
            'workURI'           => appGlobals::getWorkURI()
        ]);

        self::populateJsGlobalClient();
    }

    public static function populateJsGlobalClient()
    {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'clientId'    => appGlobals::getClientIdOfProjectRecordingTimeFor()
        ]);
    }

    public static function populateJsGlobalTaskTypeUpdateURI()
    {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'taskTypeUpdateURI' => appGlobals::getTaskTypeUpdateURI(),
            'update' => self::$update,
            'taskTypeURI' => url("/") . "/" . self::$taskTypeURI
        ]);
    }

    public static function populateJsGlobalTtvTypeClearTextTrue()
    {

        $result = Schema::hasTable('testing_selenium_variables');

        if ($result) {
            $result = \DB::table('testing_selenium_variables')->select('ttvTypeClearText')->where('id', 1)->first();

            if ($result) {
                // if the test failed, we would have a testing_selenium_variables record; ttvTypeClearText needs to = 1.
                if ($result->ttvTypeClearText === 1) {
                    // jeffery way's package for moving php variables to the .js space.
                    // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
                    // also see javascript.php in the config dir for view and .js namespace used.
                    \JavaScript::put([
                        'ttvTypeClearText' => true,
                    ]);

                    return true;
                }
            }
        }

        return false;
    }

    public static function getIsoBeginningDowDate($timeCardHoursWorkedId)
    {
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
    public static function setSessionVariableAppGlobalTimeCardTableName($timeCardHoursWorkedId)
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
