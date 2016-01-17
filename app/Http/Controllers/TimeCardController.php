<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\prepareTimeCardRequest;
use DB;
use App\Http\Controllers\TimeCardHoursWorkedController;

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

        try {
            DB::transaction(function() use ($timeCardRequestAttributes) {
                $timeCard = new TimeCard();

                $timeCard->time_card_format_id = $timeCardRequestAttributes['time_card_format_id'];
                $timeCard->work_id = $timeCardRequestAttributes['work_id'];

                $timeCard->save();

                for ($i=0;$i<appGlobals::DAYS_IN_WEEK_NUM;$i++) {
                    $timeCardHoursWorked = new TimeCardHoursWorked();
                    if ($timeCardRequestAttributes['dow_0' . $i]) {
                        $timeCardHoursWorked->work_id = $timeCardRequestAttributes['work_id'];
                        $timeCardHoursWorked->date_worked = $this->getDateWorked(appGlobals::getBeginningOfCurrentWeek($timeCardRequestAttributes['time_card_range']), $i);
                        $timeCardHoursWorked->dow = $this->getDOW($timeCard->date_worked);
                        $timeCardHoursWorked->hours_worked = $timeCardRequestAttributes['dow_0' . $i];

                        $timeCardHoursWorked->save();
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

        // get all time_card_hours_worked rows between $bwDate and $ewDate.
        $timeCardHoursWorkedRows = TimeCardHoursWorked::whereBetween('date_worked', [$bwDate, $ewDate])->get();

        // eager load task, timeCard, work, timeCardFormat and workType.
        $timeCardHoursWorkedRows->load('task');
        $timeCardHoursWorkedRows->load('timeCard');
        foreach($timeCardHoursWorkedRows as $timeCardHoursWorkedRow) {
            $timeCardHoursWorkedRow->timeCard->load('work');
            $timeCardHoursWorkedRow->timeCard->load('timeCardFormat');
            $timeCardHoursWorkedRow->timeCard->work->load('workType');
        }

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
