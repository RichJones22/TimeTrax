<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

use \App\TimeCardHoursWorked;
use \App\TimeCard;
use DB;


use App\Http\Requests;
use App\Http\Controllers\Controller;

class TimeCardHoursWorkedController extends Controller
{
    public function getTimeCardHoursWorkedData($bwDate, $ewDate) {

        $timeCardHoursWorkedRows = TimeCardHoursWorked::whereBetween('date_worked', [$bwDate, $ewDate])->get();

        // eager load timeCardFormat, work and workType.
        $timeCardHoursWorkedRows->load('task');
        $timeCardHoursWorkedRows->load('timeCard');
        foreach($timeCardHoursWorkedRows as $timeCardHoursWorkedRow) {
            $timeCardHoursWorkedRow->timeCard->load('work');

            $timeCardHoursWorkedRow->timeCard->work->load('workType');
        }

        return $timeCardHoursWorkedRows;


    }
}
