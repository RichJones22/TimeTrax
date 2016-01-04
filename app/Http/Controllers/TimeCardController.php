<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Http\Requests;
use \App\TimeCard;
use \Carbon\Carbon;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if(is_null($dateSelected)) {
            $dateSelected = Carbon::now('America/Chicago');
        } else {
            $dateSelected = new Carbon($dateSelected, 'America/Chicago');
        }

        $bwDate = new Carbon($dateSelected);
        $ewDate = new Carbon($dateSelected);

        $bwDate->startOfWeek(); // iso standards is Monday.
        $bwDate->subDay();      // Make Sunday the start.

        $ewDate->endOfWeek();   // iso standard is Sunday.
        $ewDate->subDay();      // Make Saturday the end.

        // get all time card rows between $bwDate and $ewDate.
        $timeCardRows = TimeCard::whereBetween('date_worked', [$bwDate, $ewDate])->get();

        // eager load work and workType.
        $timeCardRows->load('work');
        foreach($timeCardRows as $timeCardRow) {
            $timeCardRow->work->load('workType');
        }

        $timeCardRange = "Current ( " . $bwDate->toDateString() . " - " . $ewDate->toDateString() ." )";

//        $fontSize = 14;
//
//        $textLength = imagefontwidth($fontSize) * strlen($timeCardRange);
//
//        dd(floor($textLength*.87)+1);

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
        //
    }
}
