<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\prepareTimeCardRequest;
use DB;

use \App\Http\Requests;
use \App\Task;
use \App\TimeCard;
use \App\TimeCardHoursWorked;
use \Carbon\Carbon;
use \App\Helpers\appGlobals;

class TimeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /*
     array:12 [▼
  "time_card_format_id" => "1"
  "work_id" => "1"
  "workType" => "3"
  "dow_00" => "8"
  "dow_01" => "8"
  "dow_02" => "8"
  "dow_03" => "8"
  "dow_04" => "8"
  "dow_05" => "8"
  "dow_06" => "8"
]
     */

    private function getDateWorked($date, $i) {
        $i--;
        $newDate = new Carbon($date, 'America/Chicago');
        return $newDate->addDays($i);
    }

    private function getDOW($date) {
        if ($date->dayOfWeek == Carbon::MONDAY) {
            return 'MON';
        }
        if ($date->dayOfWeek == Carbon::TUESDAY) {
            return 'TUE';
        }
        if ($date->dayOfWeek == Carbon::WEDNESDAY) {
            return 'WED';
        }
        if ($date->dayOfWeek == Carbon::THURSDAY) {
            return 'THU';
        }
        if ($date->dayOfWeek == Carbon::FRIDAY) {
            return 'FRI';
        }
        if ($date->dayOfWeek == Carbon::SATURDAY) {
            return 'SAT';
        }
        if ($date->dayOfWeek == Carbon::SUNDAY) {
            return 'SUN';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(prepareTimeCardRequest $request)
    {
        $timeCardRequestAttributes = $request->all();

//        $myDate = appGlobals::getBeginningOfCurrentWeek($timeCardRequestAttributes['time_card_range']);
//
//        dd($this->getDateWorked($myDate, 0)->dayOfWeek);

        try {
            DB::transaction(function() use ($timeCardRequestAttributes) {
                for ($i=0;$i<appGlobals::DAYS_IN_WEEK_NUM;$i++) {
                    if ($timeCardRequestAttributes['dow_0' . $i]) {
                        $timeCard = new TimeCard();

                        $timeCard->time_card_format_id = $timeCardRequestAttributes['time_card_format_id'];
                        $timeCard->work_id = $timeCardRequestAttributes['work_id'];
                        $timeCard->date_worked = $this->getDateWorked(appGlobals::getBeginningOfCurrentWeek($timeCardRequestAttributes['time_card_range']), $i);
                        $timeCard->dow = $this->getDOW($timeCard->date_worked);
                        $timeCard->hours_worked = $timeCardRequestAttributes['dow_0' . $i];

                        $timeCard->save();
                    }
                }
            });
        } catch (Exception $e) {
            // session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText(appGlobals::INFO_TIME_VALUE_OVERLAP));
        }

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    private function hoursRange($timeCardRow, &$hoursRange) {
        if ($timeCardRow->dow == 'MON') {
            $hoursRange['MON']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'TUE') {
            $hoursRange['TUE']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'WED') {
            $hoursRange['WED']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'THR') {
            $hoursRange['THR']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'FRI') {
            $hoursRange['FRI']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'SAT') {
            $hoursRange['SAT']=$timeCardRow->hours_worked;
        }
        if ($timeCardRow->dow == 'SUN') {
            $hoursRange['SUN']=$timeCardRow->hours_worked;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dateSelected=null)
    {
        $client_id=null;


        if(is_null($dateSelected)) {
            $dateSelected = Carbon::now('America/Chicago');
        } else {
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
        }

        $bwDate = new Carbon($dateSelected);

        if ($bwDate->dayOfWeek == 0 ) {
            $ewDate = new Carbon($bwDate);
            $ewDate->addDays(6);
        } else {
            $bwDate->startOfWeek();  // iso standard; Monday is the start of week.
            $bwDate->subDay();       // adjust to Sunday as this is our current offset.

            $ewDate = new Carbon($bwDate);
            $ewDate->addDays(6);
        }

        /**
        // get all time card rows between $bwDate and $ewDate.
        $timeCardRows = TimeCard::whereBetween('date_worked', [$bwDate, $ewDate])->get();

        // eager load timeCardFormat, work and workType.
        $timeCardRows->load('work');
        $timeCardRows->load('timeCardFormat');
        foreach($timeCardRows as $timeCardRow) {
            $timeCardRow->work->load('workType');
        }
         *
         *     $data = \DB::table('project')->where('project.client_id', $client_id)
        ->join('work', 'project.id', '=', 'work.project_id')
        ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
        ->select('work_type.id', 'work_type.type', 'work.work_type_description')
        ->orderby('work.work_type_id')
        ->get();
         */

        // get all time card rows between $bwDate and $ewDate.
        $timeCardHoursWorkedRows = TimeCardHoursWorked::whereBetween('date_worked', [$bwDate, $ewDate])
            ->join('time_card', 'time_card_hours_worked.work_id', '=', 'time_card.work_id')
            ->join('work', 'time_card.work_id', '=', 'work.id')
            ->orderBy('work.work_type_description')
            ->get();

        $timeCardHoursWorkedRows->load('task');
        foreach($timeCardHoursWorkedRows as $timeCardHoursWorkedRow) {
            $timeCardHoursWorkedRow->load('timeCard');
        }


        dd($timeCardHoursWorkedRows);

//        $data = DB::table('time_card_hours_worked')
//            ->whereBetween('date_worked', [$bwDate, $ewDate])
//            ->join('time_card', 'time_card_hours_worked.work_id', '=', 'time_card.work_id')
//            ->join('work', 'time_card.work_id', '=', 'work.id')
//            ->orderBy('work.work_type_description')
//            ->get();




        // eager load timeCardFormat, work and workType.
//        $timeCardHoursWorkedRows->load('task');
//        $timeCardHoursWorkedRows->load('timeCard');
//        $timeCardHoursWorkedRows->load('timeCardFormat');
//        foreach($timeCardHoursWorkedRows as $timeCardHoursWorkedRow) {
//            $timeCardHoursWorkedRow->task->load('timeCard');
//        }


        $timeCardRange = "( " . $bwDate->toDateString() . " - " . $ewDate->toDateString() . " )";

        // jeffery way package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        \JavaScript::put([
            'timeCardURI' => appGlobals::getDomain() . appGlobals::getTimeCardURI(),
            'workURI'     => appGlobals::getWorkURI(),
            'clientId'    => appGlobals::getClientIdOfProjectRecordingTimeFor()
        ]);

        // pass the data to the view.
        return view('pages.userTimeCardView')
            ->with('timeCardRows', $timeCardHoursWorkedRows)
            ->with('timeCardRange', $timeCardRange);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back();
    }
}
