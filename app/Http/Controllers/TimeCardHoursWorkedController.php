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

        dd($timeCardHoursWorkedRows);

        /*
         // eager load timeCardFormat, work and workType.
        $timeCardRows->load('work');
        $timeCardRows->load('timeCardFormat');
        foreach($timeCardRows as $timeCardRow) {
            $timeCardRow->work->load('workType');
        }
         */

//        foreach($timeCardHoursWorkedRows as $timeCardHoursWorkedRow) {
////            $timeCardRows = DB::table('time_card')->where('work_id', $timeCardHoursWorkedRow->work_id)->get();
////            $timeCardHoursWorkedRow->timeCard = DB::table('time_card')->where('work_id', $timeCardHoursWorkedRow->work_id)->get();
////            $timeCardRows = TimeCard::where('work_id', '=', 1);
//            dd($timeCardRows);
//        }

        /*     $data = \DB::table('project')->where('project.client_id', $client_id)
            ->join('work', 'project.id', '=', 'work.project_id')
            ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
            ->select('work_type.id', 'work_type.type', 'work.work_type_description')
            ->orderby('work.work_type_id')
            ->get();
        */




        return $timeCardHoursWorkedRows;


    }
}
