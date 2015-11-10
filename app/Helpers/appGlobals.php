<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/9/15
 * Time: 4:41 PM
 */

namespace app\Helpers;

use \App\Client;
use \App\Project;
use \App\WorkType;


class appGlobals
{
    static protected $clientTableName;
    static protected $projectTableName;
    static protected $workTypeTableName;

    public function __construct() {
        self::$clientTableName = with(new Client)->getTable();
        self::$projectTableName = with(new Project)->getTable();
        self::$workTypeTableName = with(new WorkType)->getTable();
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

    static public function existsMessage($table, $text, $key) {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' exists.<br>';
    }

    static public function createdMessage($table, $text, $key) {
        echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' created.<br>';
    }
}

global $appGlobals;
$appGlobals = new appGlobals();
