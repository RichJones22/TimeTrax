@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
    use \App\Helpers\appGlobals;
?>

@section('content')

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color: darkgray;" class="tbl-h1-row-height">
                    @if (Session::has(appGlobals::getInfoMessageType()))
                        <th id="thAlertMessage" colspan="5" class="text-center"><span style="color: brown;font-weight: bold">{{ Session::get(appGlobals::getInfoMessageType()) }}</span></th>
                        <th id="thNoAlertMessage" colspan="5" class="text-center" style="display: none">{{(new Carbon($timeCard[0]->date_worked))->toFormattedDateString()}}</th>
                    @else
                        <th colspan="5" class="text-center">{{(new Carbon($timeCard[0]->date_worked))->toFormattedDateString()}}</th>
                    @endif
                </tr>
                <tr style="background-color: darkgray;">
                    <th style="padding-right: 0px;border-right-width: 0px;padding-left: 0px;border-left-width: 0px;">
                        <div>
                            <label>Type</label>
                            <div class="btn-toolbar" role="toolbar" style="float: right">
                                <div class="btn-group" role="group">
                                    {{--<div class="btn btn-secondary" style="margin-right: 5px;">--}}
                                        {{--<button type="submit" >1</button>--}}
                                    {{--</div>--}}
                                    {{--<div class="btn btn-secondary" style="margin-right: 5px;">--}}
                                        {{--<button type="submit">2</button>--}}
                                    {{--</div>--}}
                                    {{--<button type="submit" class="btn btn-secondary" style="margin-right: 5px;">1</button>--}}
                                    {{--<button type="submit" class="btn btn-secondary" style="margin-right: 5px;">2</button>--}}
                                </div>
                            </div>
                            {{--<div class="input-group col-xs-offset-0">--}}
                                {{--<label class="col-xs-2">Type</label>--}}
                                {{--<span class="input-group-btn">--}}
                                    {{--<button class="btn btn-secondary" type="submit" style="margin-right: 5px;">1</button>--}}
                                    {{--<button class="btn btn-secondary" type="submit" >2</button>--}}
                                {{--</span>--}}
                            {{--</div>--}}
                            {{--<div class="btn-toolbar" role="toolbar">--}}
                                {{--<section class="row col-xs-offset-0">--}}
                                    {{--@if (Session::has(appGlobals::getTimeCardTableName()))--}}
                                        {{--{!! Form::open(array('route' => array('timeCard.show', appGlobals::getIsoBeginningDowDate(Session::get(appGlobals::getTimeCardTableName()))))) !!}--}}
                                        {{--<input type="hidden" name="_method" value="GET">--}}
                                        {{--<input hidden type="text" name="_token" value="{{ csrf_token() }}">--}}
                                        {{--<button id="routeToTimeCardView" type ="button" class = "btn btn-secondary btn-primary btn-xs" style="float: right;">--}}
                                            {{--<span class="glyphicon glyphicon-step-backward"></span>--}}
                                        {{--</button>--}}
                                        {{--{!! Form::close() !!}--}}
                                    {{--@endif--}}
                                    {{--@if(count($tasks)>0)--}}
                                        {{--{{Session::put(appGlobals::getTaskTableName(), $timeCardHoursWorkedId)}}--}}
                                        {{--{!! Form::open(array('route' => array('taskType.task.show', $tasks[0]->TaskType->client_id, $timeCardHoursWorkedId))) !!}--}}
                                        {{--<input type="hidden" name="_method" value="GET">--}}
                                        {{--<button id="routeToTaskTypeView" type ="button" class = "btn btn-secondary btn-primary btn-xs" style="float: right">--}}
                                            {{--<span class="glyphicon glyphicon-open"></span>--}}
                                        {{--</button>--}}
                                        {{--{!! Form::close() !!}--}}
                                    {{--@endif--}}
                                {{--</section>--}}
                            {{--</div>--}}
                            {{--<section class="row col-xs-offset-0">--}}
                                {{--@if (Session::has(appGlobals::getTimeCardTableName()))--}}
                                    {{--{!! Form::open(array('route' => array('timeCard.show', appGlobals::getIsoBeginningDowDate(Session::get(appGlobals::getTimeCardTableName()))))) !!}--}}
                                    {{--<input type="hidden" name="_method" value="GET">--}}
                                    {{--<input hidden type="text" name="_token" value="{{ csrf_token() }}">--}}
                                    {{--<button id="routeToTimeCardView" type ="button" class = "btn btn-secondary btn-primary btn-xs" style="float: right;">--}}
                                        {{--<span class="glyphicon glyphicon-step-backward"></span>--}}
                                    {{--</button>--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--@endif--}}
                                {{--@if(count($tasks)>0)--}}
                                    {{--{{Session::put(appGlobals::getTaskTableName(), $timeCardHoursWorkedId)}}--}}
                                    {{--{!! Form::open(array('route' => array('taskType.task.show', $tasks[0]->TaskType->client_id, $timeCardHoursWorkedId))) !!}--}}
                                    {{--<input type="hidden" name="_method" value="GET">--}}
                                    {{--<button id="routeToTaskTypeView" type ="button" class = "btn btn-secondary btn-primary btn-xs" style="float: right">--}}
                                        {{--<span class="glyphicon glyphicon-open"></span>--}}
                                    {{--</button>--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--@endif--}}
                            {{--</section>--}}
                            {{--<div class="col-xs-2" >--}}
                                {{--@if (Session::has(appGlobals::getTimeCardTableName()))--}}
                                    {{--{!! Form::open(array('route' => array('timeCard.show', appGlobals::getIsoBeginningDowDate(Session::get(appGlobals::getTimeCardTableName()))))) !!}--}}
                                    {{--<input type="hidden" name="_method" value="GET">--}}
                                    {{--<input hidden type="text" name="_token" value="{{ csrf_token() }}">--}}
                                    {{--<button id="routeToTimeCardView" type ="submit" class = "btn btn-primary btn-xs" style="float: right">--}}
                                        {{--<span class="glyphicon glyphicon-step-backward"></span>--}}
                                    {{--</button>--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-2" style="padding-left: 0;float: left">--}}
                                {{--@if(count($tasks)>0)--}}
                                    {{--{{Session::put(appGlobals::getTaskTableName(), $timeCardHoursWorkedId)}}--}}
                                    {{--{!! Form::open(array('route' => array('taskType.task.show', $tasks[0]->TaskType->client_id, $timeCardHoursWorkedId))) !!}--}}
                                    {{--<input type="hidden" name="_method" value="GET">--}}
                                    {{--<button id="routeToTaskTypeView" type ="submit" class = "btn btn-primary btn-xs" style="float: right">--}}
                                        {{--<span class="glyphicon glyphicon-open"></span>--}}
                                    {{--</button>--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        </div>
                    </th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th><span style="color: blue; font-weight: bold"> ( {{ $totalHoursWorked }} ) </span>Hours Worked</th>
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Notes</span>
                        {!! Form::open(array('route' => array('task.show', $timeCardHoursWorkedId))) !!}
                            <input type="hidden" name="_method" value="GET">
                            <button type ="submit" class = "btn btn-primary btn-xs" id="refresh" style="float: right">
                                   <span class="glyphicon glyphicon-refresh"></span>
                            </button>
                        {!! Form::close() !!}
                    </th>
                </tr>
                {!! Form::open(array('route' => array('task.create'))) !!}
                    <input type="hidden" name="_method" value="POST">
                    <input hidden type="text" name="time_card_hours_worked_id" value="{{$timeCardHoursWorkedId}}">
                    <div>
                        <tr class="info">
                            <th>
                                <select id="taskType" name ="taskType" class="form-control col-xs-12">
                                    <option value="0">--Select Type--</option>
                                </select>
                            </th>
                            <th><input class="form-control" id="startt-search" name="startt" placeholder="start"></th>
                            <th><input class="form-control" id="endt-search" name="endt" placeholder="end"></th>
                            <th><input disabled type="text" class="form-control" id="hoursWorked" name="hoursWorked"></th>
                            <th>
                                <div class="col-xs-9" style="display: inline-block;">
                                    <input type="text" class="form-control" id="notes" placeholder="notes" name="notes">
                                </div>
                                <button disabled type="submit" class="btn btn-primary col-xs" id="saveButton" style="float: right">Save</button>
                            </th>
                        </tr>
                    </div>
                {!! Form::close() !!}
            </thead>

            <tbody id="taskTable">
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->TaskType->type }}</td>
                        <td>{{ (new Carbon($task->start_time))->format('H:i') }}</td>
                        <td>{{ (new Carbon($task->end_time))->format('H:i') }}</td>
                        <td>{{ $task->hours_worked }}</td>
                        <td>
                            <div class="col-xs-9" style="display: inline-block;">
                                {{ $task->notes }}
                            </div>
                            {!! Form::open(array('route' => array('task.destroy', $task->id))) !!}
                                <input type="hidden" name="_method" value="POST">
                                <button type ="submit" class = "btn btn-danger btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @unless(count($tasks))
            <p class="text-center">No Tasks have been added as yet</p>
        @endunless

    </div>

@stop