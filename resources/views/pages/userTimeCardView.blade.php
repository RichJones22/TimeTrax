@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
    use \App\Helpers\appGlobals;

    $timeCardFormats = ['dow_00' => 'SUN',
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
                        {{--<th colspan="9" class="text-center">{{$timeCardRange}}</th>--}}
                        <form method="post" action="{{ route('timeCard.create', 1) }}">
                            <th colspan="9">
                                <select class="form-control center-block" style="background: darkgray; width: 315px; height: 20px">
                                    <option value="0">{{$timeCardRange}}</option>
                                </select>
                            </th>
                        </form>
                    @endif
                </tr>
                <tr style="background-color: darkgray;">
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Type</span>
                        {{--@if(count($workType)>0)--}}
                            {{--{{Session::put('from_taskView', $timeCardId)}}--}}
                            {{--<form method="get" action="{{ route('taskType.show', [$tasks[0]->TaskType->client_id, $timeCardId])}}">--}}
                                {{--<button id="routeToTaskTypeView" type ="submit" class = "btn btn-primary btn-xs" style="float: right">--}}
                                    {{--<span class="glyphicon glyphicon-open"></span>--}}
                                {{--</button>--}}
                            {{--</form>--}}
                        {{--@endif--}}
                    </th>
                    <th>{{$timeCardFormats['dow_00']}}</th>
                    <th>{{$timeCardFormats['dow_01']}}</th>
                    <th>{{$timeCardFormats['dow_02']}}</th>
                    <th>{{$timeCardFormats['dow_03']}}</th>
                    <th>{{$timeCardFormats['dow_04']}}</th>
                    <th>{{$timeCardFormats['dow_05']}}</th>
                    <th>{{$timeCardFormats['dow_06']}}</th>
                    <th></th>
                    {{--<th>--}}
                        {{--<span class="col-xs-9" style="display: inline-block;">Notes</span>--}}
                        {{--<form method="get" action="{{ route('task.show', $timeCardId) }}">--}}
                            {{--<button type ="submit" class = "btn btn-primary btn-xs" style="float: right">--}}
                                   {{--<span class="glyphicon glyphicon-refresh"></span>--}}
                            {{--</button>--}}
                        {{--</form>--}}
                    {{--</th>--}}
                </tr>
                <form method="post" action="{{ route('timeCard.create', 1) }}">
                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <tr class="info">
                            <th>
                                <select id="workType" name ="workType" class="form-control col-xs-12">
                                    <option value="0">--Work Type--</option>
                                </select>
                            </th>
                            <th style="width: 75px"><input class="form-control" id="dow_00" name="dow_00" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_01" name="dow_01" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_02" name="dow_02" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_03" name="dow_03" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_04" name="dow_04" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_05" name="dow_05" placeholder="0"></th>
                            <th style="width: 75px"><input class="form-control" id="dow_05" name="dow_06" placeholder="0"></th>
                            <th style="width: 150px">
                                {{--<div class="col-xs-9" style="display: inline-block;">--}}
                                    {{--<input class="form-control" id="dow_06" name="dow_06">--}}
                                {{--</div>--}}
                                <button disabled type="submit" class="btn btn-primary col-xs" id="saveWorkType" style="float: right">Save</button>
                            </th>
                        </tr>
                    </div>
                </form>
            </thead>

            @if (count($timeCardRows) > 0)
                <tbody id="timeCardTable">
                    @foreach ($timeCardRows as $timeCardRow)
                        <tr>
                            <td>{{$timeCardRow->Work->WorkType->type}}--{{$timeCardRow->Work->work_type_description}}</td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_00'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_01'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_02'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_03'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_04'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if ($timeCardRow->dow == $timeCardFormats['dow_05'])
                                    {{$timeCardRow->total_hours_worked}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                <div class="col-xs-9" style="display: inline-block;">
                                    @if ($timeCardRow->dow == $timeCardFormats['dow_06'])
                                        {{$timeCardRow->total_hours_worked}}
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
            <p class="text-center">No Work have been added as yet</p>
        @endunless

    </div>

@stop