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
use \App\TaskType;
use \App\Task;

use \Carbon\Carbon;


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
    static protected $timeTaskTypeTableName;
    static protected $timeTaskTableName;

    // routes used by both javascript and php
    static protected $domain="http://timetrax.dev/";
    static protected $timeCardURI="timeCard/";

    static protected $infoMessageType = 'info_message';
    static protected $messageText = [
        // app info messages
        self::INFO_TIME_VALUE_OVERLAP => 'One of your entered time values overlaps with existing data.  Your data has been refreshed.',
        self::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD => 'Type restricted to one word.',
        self::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS => 'Type already exists.  Your data has been refreshed.',
        self::TBL_TASK_TYPE_CONSTRAINT_VIOLATION => "Type (%s) currently exists on tasks.  Can't delete.",
    ];

    //used for testing Relational Database Management System fail cases.
    // set $testRDBMS = true to test the SeleniumRDBMSTest.php file.
    static protected $testRDBMS = false;

    public function __construct() {
        self::$clientTableName = with(new Client)->getTable();
        self::$projectTableName = with(new Project)->getTable();
        self::$workTypeTableName = with(new WorkType)->getTable();
        self::$timeCardFormatTableName = with(new TimeCardFormat)->getTable();
        self::$workTableName = with(new Work)->getTable();
        self::$timeCardTableName = with(new TimeCard)->getTable();
        self::$timeTaskTypeTableName = with(new TaskType)->getTable();
        self::$timeTaskTableName = with(new Task)->getTable();
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

    static public function getTaskTypeTableName() {
        return self::$timeTaskTypeTableName;
    }

    static public function getTaskTableName() {
        return self::$timeTaskTableName;
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

    static public function getTestRDBMS() {
        return self::$testRDBMS;
    }

    static public function getDomain() {
        return self::$domain;
    }

    static public function getTimeCardURI() {
        return self::$timeCardURI;
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

}

global $appGlobals;
$appGlobals = new appGlobals();
