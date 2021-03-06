<?php

namespace App\Http\Controllers;

use App\Http\Requests\prepareTimeCardRequest;
use App\TimeCard;
use App\TimeCardHoursWorked;
use Carbon\Carbon;
use App\Helpers\appGlobals;
use Illuminate\Support\Facades\DB;

/**
 * Class TimeCardController.
 */
class TimeCardController extends Controller
{
    /**
     * create time card entries.
     *
     * param $request
     * param $timeCardRange
     *
     * @return \Illuminate\Http\Response
     */
    public function create(prepareTimeCardRequest $request, $timeCardRange)
    {
        $timeCardRequestAttributes = $request->all();

        $this->createTimeCardData($timeCardRange, $timeCardRequestAttributes);

        return redirect()->back();
    }

    /**
     * show the time card.
     *
     * @param  $dateSelected
     *
     * @return \Illuminate\View\View
     *
     * @internal param $request
     */
    public function show($dateSelected = null)
    {
        // get beginning and ending week dates.
        list($bwDate, $ewDate, $iso_beginning_dow_date) = $this->getBeginningAndEndingWeekDates($dateSelected);

        // get time_card data
        list($timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow) = $this->getTimeCardData($iso_beginning_dow_date);

        // build view variables.
        $timeCardRange = $this->buildViewVariables($timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow, $bwDate, $ewDate);

        // set values use by appGlobal class.
        $this->setValueUseByAppGlobal($timeCardRows);

        // pass the data to the view.
        return view('pages.userTimeCardView')
            ->with('timeCardRows', $timeCardRows)
            ->with('timeCardRange', $timeCardRange);
    }

    /**
     * delete a time card entry.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            // remove all task rows
            DB::table('task')->join('time_card_hours_worked', 'task.time_card_hours_worked_id', '=', 'time_card_hours_worked.id')
                ->where('time_card_id', $id)->delete();

            // remove all time_card_hours_worked rows
            DB::table('time_card_hours_worked')->where('time_card_id', $id)->delete();

            // remove time_card row.
            DB::table('time_card')->where('id', $id)->delete();
        });

        return redirect()->back();
    }

  /*********************************************************************************************************************
  * supporting routines
  *********************************************************************************************************************/

    /**
     * @param $date
     * @param $i
     *
     * @return Carbon
     */
    private function getDateWorked($date, $i)
    {
        --$i;
        $newDate = new Carbon($date, 'America/Chicago');

        return $newDate->addDays($i);
    }

    /**
     * @param $date
     *
     * @return string
     */
    private function getDOW($date)
    {
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

        return $this;
    }

    /**
     * @param $workTypeId
     *
     * @return mixed
     */
    private function getClientId($workTypeId)
    {
        $data = DB::table('work_type')->where('work_type.id', $workTypeId)
            ->select('client_id')
            ->first();

        foreach ($data as $k => $v) {
            return $v;
        }

        return $this;
    }

    /**
     * @param $clientId
     *
     * @return mixed
     */
    private function getTimeCardFormatId($clientId)
    {
        $data = DB::table('time_card_format')->where('client_id', $clientId)
            ->select('time_card_format.id')
            ->first();

        foreach ($data as $k => $v) {
            return $v;
        }

        return $this;
    }

    /**
     * @param $workTypeId
     *
     * @return mixed
     */
    private function getWorkIdViaWorkTypeId($workTypeId)
    {
        $data = DB::table('work')->where('work_type_id', $workTypeId)
            ->select('work.id')
            ->first();

        foreach ($data as $k => $v) {
            return $v;
        }

        return $this;
    }

    /**
     * @param $dateSelected
     *
     * @return array
     */
    protected function getBeginningAndEndingWeekDates($dateSelected)
    {
        $dateSelected = $this->deriveDateSelected($dateSelected);
        $bwDate = new Carbon($dateSelected);

        if ($bwDate->dayOfWeek == 0) {
            $ewDate = new Carbon($bwDate);
            $iso_beginning_dow_date = new Carbon($bwDate);
            $ewDate->addDays(6);
        } else {
            $bwDate->startOfWeek();  // iso standard; Monday is the start of week.
            $iso_beginning_dow_date = new Carbon($bwDate);
            $bwDate->subDay();       // adjust to Sunday as this is our current offset.

            $ewDate = new Carbon($bwDate);
            $ewDate->addDays(6);
        }

        return [$bwDate, $ewDate, $iso_beginning_dow_date];
    }

    /**
     * @param $hoursWorkedPerWorkId
     * @param $hoursWorkedDow
     * @param $hoursWorkedIdDow
     *
     * @return array
     */
    protected function deriveHoursWorkDowAndHoursWorkedIdDow($hoursWorkedPerWorkId)
    {
        // create arrays for:
        // - hours worked for dow
        // - hours worked id for dow
        $hoursWorkedDow[] = [];
        $hoursWorkedIdDow[] = [];

        foreach ($hoursWorkedPerWorkId as $hoursWorked) {
            foreach ($hoursWorked as $hoursWork) {
                $hoursWorkedDow[$hoursWork->work_id][$hoursWork->dow] = $hoursWork->hours_worked;
                $hoursWorkedIdDow[$hoursWork->work_id][$hoursWork->dow] = $hoursWork->id;
            }
        }
        array_shift($hoursWorkedDow);
        array_shift($hoursWorkedIdDow);

        return [$hoursWorkedDow, $hoursWorkedIdDow];
    }

    /**
     * @param $timeCardRows
     */
    protected function egerloadRelations($timeCardRows)
    {
        foreach ($timeCardRows as $timeCardRow) {
            $timeCardRow->load('work');
            $timeCardRow->load('timeCardFormat');
            $timeCardRow->work->load('workType');
        }
    }

    /**
     * @param $timeCardRows
     * @param $hoursWorkedDow
     * @param $hoursWorkedIdDow
     */
    protected function buildTimeCardRowsVar($timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow)
    {
        for ($i = 0; $i < count($timeCardRows); ++$i) {
            $timeCardRows[$i]->timeCardHoursWorked = $hoursWorkedDow[$i];
            $timeCardRows[$i]->timeCardHoursWorkedId = $hoursWorkedIdDow[$i];
            $timeCardRows[$i]->timeCardWorkId = $timeCardRows[$i]->Work->work_type_id;
        }
    }

    /**
     * @param $bwDate
     * @param $ewDate
     *
     * @return string
     */
    protected function buildTimeCardRangeVar(Carbon $bwDate, Carbon $ewDate)
    {
        $timeCardRange = '( '.$bwDate->toDateString().' - '.$ewDate->toDateString().' )';

        return $timeCardRange;
    }

    /**
     * @param $iso_beginning_dow_date
     *
     * @return array
     */
    protected function getTimeCardData($iso_beginning_dow_date)
    {
        // get all time_card rows between $bwDate and $ewDate.
        $timeCardRows = TimeCard::getTimeCardRows($iso_beginning_dow_date);

        // derive time_card_hours data via bwDate and ewDate
        $hoursWorkedPerWorkId = TimeCardHoursWorked::deriveTimeCardHoursWorkedFromBeginningAndEndingWeekDates($timeCardRows, $iso_beginning_dow_date);

        // derive $hoursWorkedDow, $hoursWorkedIdDow
        list($hoursWorkedDow, $hoursWorkedIdDow) = $this->deriveHoursWorkDowAndHoursWorkedIdDow($hoursWorkedPerWorkId);

        // eager load related data.
        $this->egerloadRelations($timeCardRows);

        return [$timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow];
    }

    /**
     * @param $timeCardRows
     * @param $hoursWorkedDow
     * @param $hoursWorkedIdDow
     * @param $bwDate
     * @param $ewDate
     *
     * @return string
     */
    protected function buildViewVariables($timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow, $bwDate, $ewDate)
    {
        // build timeCardRows var.
        $this->buildTimeCardRowsVar($timeCardRows, $hoursWorkedDow, $hoursWorkedIdDow);

        // build the timeCardRange var
        $timeCardRange = $this->buildTimeCardRangeVar($bwDate, $ewDate);

        return $timeCardRange;
    }

    /**
     * @param $timeCardRange
     * @param $timeCardRequestAttributes
     *
     * @return TimeCard
     */
    protected function saveTimeCard($timeCardRange, $timeCardRequestAttributes)
    {
        $timeCard = new TimeCard();

        $timeCard->setIsoBeginningDowDate(appGlobals::getBeginningOfCurrentWeek($timeCardRange));
        $timeCard->setWorkId($this->getWorkIdViaWorkTypeId($timeCardRequestAttributes['workType']));
        $timeCard->setTimeCardFormatId($this->getTimeCardFormatId($this->getClientId($timeCardRequestAttributes['workType'])));

        if ($timeCard->rowExists()) {
            $timeCard = $timeCard->row;
        }

        $timeCard->save();

        return $timeCard;
    }

    /**
     * @param $timeCard
     * @param $timeCardRange
     * @param $i
     * @param $timeCardRequestAttributes
     */
    protected function saveTimeCardHoursWorked($timeCard, $timeCardRange, $i, $timeCardRequestAttributes)
    {
        $timeCardHoursWorked = new TimeCardHoursWorked();

        $timeCardHoursWorked->setWorkId($timeCard->work_id);
        $timeCardHoursWorked->setTimeCardId($timeCard->id);
        $timeCardHoursWorked->setDateWorked($this->getDateWorked(appGlobals::getBeginningOfCurrentWeek($timeCardRange), $i));
        $timeCardHoursWorked->setDow($this->getDOW($timeCardHoursWorked->getDateWorked()));
        $timeCardHoursWorked->setHoursWorked($timeCardRequestAttributes['dow_0'.$i]);

        $timeCardHoursWorked->save();
    }

    /**
     * @param $timeCardRange
     * @param $timeCardRequestAttributes
     */
    private function createTimeCardData($timeCardRange, $timeCardRequestAttributes)
    {

        // check if getTestRDBMS is set for testing the Database triggers.
        if (appGlobals::getTestRDBMS()) {
            $this->createTimeCardDataTransaction($timeCardRange, $timeCardRequestAttributes);
        } else {
            try {
                $this->createTimeCardDataTransaction($timeCardRange, $timeCardRequestAttributes);
            } catch (\Exception $e) {
                $this->timeOverlapError(appGlobals::INFO_TIME_VALUE_OVERLAP);
            }
        }
    }

    /**
     * set session flash message for appGlobals::INFO_TIME_VALUE_OVERLAP message.
     *
     * @param $messageNum
     */
    private function timeOverlapError($messageNum)
    {
        session()->forget(appGlobals::getInfoMessageType());
        session()->flash(appGlobals::getInfoMessageType(), appGlobals::getInfoMessageText($messageNum));
    }

    /**
     * @param $timeCardRange
     * @param $timeCardRequestAttributes
     */
    private function createTimeCardDataTransaction($timeCardRange, $timeCardRequestAttributes)
    {
        DB::transaction(function () use ($timeCardRequestAttributes, $timeCardRange) {
            $timeCard = $this->saveTimeCard($timeCardRange, $timeCardRequestAttributes);

            for ($i = 0; $i < appGlobals::DAYS_IN_WEEK_NUM; ++$i) {
                if ($timeCardRequestAttributes['dow_0'.$i]) {
                    $this->saveTimeCardHoursWorked($timeCard, $timeCardRange, $i, $timeCardRequestAttributes);
                }
            }
        });
    }

    /**
     * set the refresh button on the Time View to use the correct timeCardWorkId.
     *
     * @param $timeCardRows
     */
    private function setRefreshButtonToCorrectTimeCardWorkedId($timeCardRows)
    {
        foreach ($timeCardRows as $timeCardRow) {
            $timeCardHoursWorkedIdRows = $timeCardRow->timeCardHoursWorkedId;
            foreach ($timeCardHoursWorkedIdRows as $timeCardHoursWorkedId) {
                appGlobals::setSessionVariableAppGlobalTimeCardTableName($timeCardHoursWorkedId);
                break 2; // only need the first occurrence...
            }
        }
    }

    /**
     * @param $timeCardRows
     *
     * @internal param Request $request
     */
    private function setValueUseByAppGlobal($timeCardRows)
    {
        // jeffery way's package for moving php variables to the .js space.
        // see https://github.com/laracasts/PHP-Vars-To-Js-Transformer.
        // also see javascript.php in the config dir for view and .js namespace used.
        appGlobals::populateJsGlobalSpace();

        // used for routing.
        $this->setRefreshButtonToCorrectTimeCardWorkedId($timeCardRows);
    }

    /**
     * @param $dateSelected
     * @return Carbon
     */
    protected function deriveDateSelected($dateSelected):Carbon
    {
        if (is_null($dateSelected)) {
            $dateSelected = Carbon::now('America/Chicago');
            return $dateSelected;
        } else {
            try {
                $dateSelected = new Carbon($dateSelected, 'America/Chicago');
                return $dateSelected;
            } catch (\Exception $e) {
                abort(404, 'Invalid date selected in URL');
            }
            return $dateSelected;
        }
    }
}
