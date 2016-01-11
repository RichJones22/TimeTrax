@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
    use \App\Helpers\appGlobals;

    $timeCardFormats = [
                'dow_00' =>'SUN',
                'dow_01' =>'MON',
                'dow_02' =>'TUE',
                'dow_03' =>'WED',
                'dow_04' =>'THU',
                'dow_05' =>'FRI',
                'dow_06' =>'SAT',
    ]
?>

@section('content')

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color: darkgray;">
                    @if (Session::has(appGlobals::getInfoMessageType()))
                        <th id="thAlertMessage" colspan="9" class="text-center"><span style="color: brown;font-weight: bold">{{ Session::get(appGlobals::getInfoMessageType()) }}</span></th>
                        <th id="thNoAlertMessage" colspan="9" class="text-center" style="display: none">{{$timeCardRange}}</th>
                    @else
                        <th colspan="9" class="text-center">
                            <div class="tbl-h1-height">
                                <span style="display: inline-block;">
                                    {!! Form::open(array('route' => array('timeCard.show', appGlobals::getBeginningOfPreviousWeek($timeCardRange)))) !!}
                                    <input type="hidden" name="_method" value="GET">
                                        <button type ="submit" style="background: darkgray">
                                            <span class="glyphicon glyphicon-triangle-left"></span>
                                        </button>
                                    {!! Form::close() !!}
                                </span>
                                <span style="display: inline-block;">
                                    {!! Form::open(array('route' => array('timeCard.show', appGlobals::getBeginningOfCurrentWeek($timeCardRange))
                                                                         ,'id'    => 'formNext')) !!}
                                        <input type="hidden" name="_method" value="GET">
                                        <input id="specificDay" type="hidden" name="specificDay" value="">
                                        <input id="timeCardCalendar" style="background: darkgray; border: inset; width: 185px" value="{{$timeCardRange}}">
                                    {!! Form::close() !!}
                                </span>
                                <span style="display: inline-block;">
                                    {!! Form::open(array('route' => array('timeCard.show', appGlobals::getBeginningOfNextWeek($timeCardRange)))) !!}
                                    <input type="hidden" name="_method" value="GET">
                                        <button type ="submit" style="background: darkgray">
                                            <span class="glyphicon glyphicon-triangle-right"></span>
                                        </button>
                                    {!! Form::close() !!}
                                </span>
                            </div>
                        </th>
                    @endif
                </tr>
                <tr style="background-color: darkgray;" class="tbl-h2-height">
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Work Type</span>
                    </th>
                    <th>{{$timeCardFormats['dow_00']}}</th>
                    <th>{{$timeCardFormats['dow_01']}}</th>
                    <th>{{$timeCardFormats['dow_02']}}</th>
                    <th>{{$timeCardFormats['dow_03']}}</th>
                    <th>{{$timeCardFormats['dow_04']}}</th>
                    <th>{{$timeCardFormats['dow_05']}}</th>
                    <th>{{$timeCardFormats['dow_06']}}</th>
                    <th></th>
                </tr>
                <tr class="info tbl-h2-height">
                    {!! Form::open(array('route' => array('timeCard.create', appGlobals::getBeginningOfCurrentWeek($timeCardRange)))) !!}
                        <input type="hidden" name="_method" value="POST">
                        @if (count($timeCardRows) > 0)
                            <input type="hidden" name="time_card_format_id" value="{{$timeCardRows[0]->timeCardFormat->id}}">
                            <input type="hidden" name="work_id" value="{{$timeCardRows[0]->work->id}}">
                        @endif
                        <th>
                            <select id="workType" name ="workType" class="form-control col-xs-12">
                                <option value="0">--Select Type--</option>
                            </select>
                        </th>
                        <th style="width: 75px"><input class="form-control" id="dow_00" name="dow_00" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_01" name="dow_01" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_02" name="dow_02" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_03" name="dow_03" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_04" name="dow_04" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_05" name="dow_05" placeholder="0"></th>
                        <th style="width: 75px"><input class="form-control" id="dow_06" name="dow_06" placeholder="0"></th>
                        <th style="width: 150px">
                            <button disabled type="submit" class="btn btn-primary col-xs" id="saveButtonTimeCard" style="float: right">Save</button>
                        </th>
                    {!! Form::close() !!}
                </tr>
            </thead>

            @if (count($timeCardRows) > 0)
                <tbody id="timeCardTable">
                    @foreach ($timeCardRows as $timeCardRow)
                        <tr>
                            <td>{{$timeCardRow->Work->WorkType->type}}--{{$timeCardRow->Work->work_type_description}}</td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_00'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_01'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_02'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_03'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_04'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_05'])
                                    {{$timeCardRow->hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                <div class="col-xs-9" style="display: inline-block;">
                                    @if ($timeCardRow->dow == $timeCardFormats['dow_06'])
                                        {{$timeCardRow->hours_worked}}
                                    @else
                                        {{0}}
                                    @endif
                                </div>
                                {{--<form method="post" action="{{ route('task.destroy', $task->id) }}">--}}
                                    {{--<input hidden type="text" name="_token" value="{{ csrf_token() }}">--}}
                                    {{--<button type ="submit" class = "btn btn-danger btn-xs" style="float: right">--}}
                                        {{--<span class="glyphicon glyphicon-trash"></span>--}}
                                    {{--</button>--}}
                                {{--</form>--}}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>

        @unless(count($timeCardRows))
            <p class="text-center">No work has been added to time card as yet</p>
        @endunless

    </div>

@stop