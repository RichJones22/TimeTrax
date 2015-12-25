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


class appGlobals
{
    const DAYS_IN_WEEK_NUM = 7;
    const TBL_TASK_START_TIME_GT_END_TIME = '45001';

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

    static protected $infoMessageType = 'info_message';
    static protected $messageText = [
        // app info messages
        self::INFO_TIME_VALUE_OVERLAP => 'One of your entered time values overlaps with existing data.  Your data has been refreshed.',
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

}

global $appGlobals;
$appGlobals = new appGlobals();
