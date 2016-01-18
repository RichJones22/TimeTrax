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

    private function getDateWorked($date, $i) {
        $i--;
        $newDate = new Carbon($date, 'America/Chicago');
        return $newDate->addDays($i);
    }

    private function getDOW($date) {

        $date = new Carbon($date, 'America/Chicago');

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

    private function getWorkIdViaWorkTypeId($workType, $timeCardRange) {
        $bwDate = new Carbon(appGlobals::getBeginningOfCurrentWeek($timeCardRange), 'America/Chicago');
        $bwDate->subDay();
        $ewDate = new Carbon($bwDate, 'America/Chicago');
        $ewDate->addDays(6);

        $data = $timeCardRows = TimeCard::whereBetween('time_card_hours_worked.date_worked', [$bwDate->toDateString(), $ewDate->toDateString()])
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->join('work', 'time_card.work_id', '=', 'work.id')
            ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
            ->where('time_card_hours_worked.hours_worked', ">", 0)
            ->where('work_type.id', $workType)
            ->first();

        if ($data) {
            return $data->work_id;
        } else {
            return $workType;
        }

//
//        return ($data->work_id) ? $workType : $data->work_id;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(prepareTimeCardRequest $request, $timeCardRange)
    {
        $timeCardRequestAttributes = $request->all();

        dd($timeCardRequestAttributes);

        try {
            DB::transaction(function() use ($timeCardRequestAttributes, $timeCardRange) {
                $timeCard = new TimeCard();

//                $timeCard->iso_beginning_dow_date = appGlobals::getBeginningOfCurrentWeek($timeCardRequestAttributes['time_card_range']);
                $timeCard->iso_beginning_dow_date = appGlobals::getBeginningOfCurrentWeek($timeCardRange);
                $timeCard->work_id = $timeCardRequestAttributes['workType'];
                $timeCard->time_card_format_id = $timeCardRequestAttributes['time_card_format_id'];

                if (is_null($timeCard = TimeCard::checkIfExists($timeCard))) {
                    $timeCard->save();
                }

                for ($i=0;$i<appGlobals::DAYS_IN_WEEK_NUM;$i++) {
                    $timeCardHoursWorked = new TimeCardHoursWorked();
                    if ($timeCardRequestAttributes['dow_0' . $i]) {
//                        $timeCardHoursWorked->time_card_id = $this->getWorkIdViaWorkTypeId($timeCardRequestAttributes['workType'], $timeCardRequestAttributes['time_card_range']);
//                        $timeCardHoursWorked->date_worked = $this->getDateWorked(appGlobals::getBeginningOfCurrentWeek($timeCardRequestAttributes['time_card_range']), $i);
                        $timeCardHoursWorked->time_card_id = $this->getWorkIdViaWorkTypeId($timeCardRequestAttributes['workType'], $timeCardRange);
                        $timeCardHoursWorked->date_worked = $this->getDateWorked(appGlobals::getBeginningOfCurrentWeek($timeCardRange), $i);
                        $timeCardHoursWorked->dow = $this->getDOW($timeCardHoursWorked->date_worked);
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


        // get all time_card rows between $bwDate and $ewDate.
        $timeCardRows = TimeCard::whereBetween('time_card_hours_worked.date_worked', [$bwDate, $ewDate])
            ->join('time_card_hours_worked', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
            ->join('work', 'time_card.work_id', '=', 'work.id')
            ->join('work_type', 'work.work_type_id', '=', 'work_type.id')
            ->where('time_card_hours_worked.hours_worked', ">", 0)
            ->select('time_card.id', 'time_card.iso_beginning_dow_date', 'time_card.work_id','time_card.time_card_format_id')
//            ->select('time_card.work_id', 'time_card.time_card_format_id', 'time_card_hours_worked.id', 'time_card_hours_worked.time_card_id')
            ->groupBy('work_type.type')
            ->orderBy('work_type.type')
            ->get();

        $hoursWorkedPerWorkId = [];

        // populate the time_card_hours_worked data by $timeCardRow->id.
        foreach($timeCardRows as $timeCardRow) {
            $hoursWorkedPerWorkId[$timeCardRow->id] = TimeCardHoursWorked::whereBetween('time_card_hours_worked.date_worked', [$bwDate, $ewDate])
                ->join('time_card', 'time_card_hours_worked.time_card_id', '=', 'time_card.id')
                ->where('time_card_hours_worked.hours_worked', ">", 0)
                ->where('time_card_hours_worked.time_card_id', '=', $timeCardRow->id)
//                ->groupBy('time_card.work_id')
//                ->orderBy('time_card.work_id')
                ->get();
        }


        $temp[] = [];

        foreach($hoursWorkedPerWorkId as $hoursWorked) {
            foreach($hoursWorked as $hoursWork) {
                $temp[$hoursWork->work_id][$hoursWork->dow] = $hoursWork->hours_worked;
            }
        }
        array_shift($temp);

        // attached $hoursWorkedPerWorkId to the instance of $timeCardRows[$i]->timeCardHoursWorked
        for($i=0;$i<count($timeCardRows);$i++) {
            $timeCardRows[$i]->timeCardHoursWorked = $temp[$i];
        }

        // eager load work, timeCardFormat and workType.
        foreach($timeCardRows as $timeCardRow) {
            $timeCardRow->load('work');
            $timeCardRow->load('timeCardFormat');
            $timeCardRow->work->load('workType');
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
            ->with('timeCardRows', $timeCardRows)
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
