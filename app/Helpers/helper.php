<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/8/15
 * Time: 2:22 PM
 */

use \App\Client;
use \App\Project;
use \App\WorkType;

 /**
 * display exists message.
 * - note: the the successfully created message is in class specific Observer.
 * @param $text
 * @param $key
 */
function existsMessage($table, $text, $key)
{
    echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' exists.<br>';
}

function createdMessage($table, $text, $key)
{
    echo $table . ": " . "'" . $text . "'" . " with key of " . $key . ' created.<br>';
}

function clientTableName()
{
    return with(new Client)->getTable();
}

function projectTableName()
{
    return with(new Project)->getTable();
}

function workTypeTableName()
{
    return with(new WorkType)->getTable();
}
