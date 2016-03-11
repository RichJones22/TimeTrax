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
                    <th style="padding-right: 5px;border-right-width: 0px;border-left-width: 0px;">
                        <label style="padding-bottom: 0px;margin-bottom: 0px;">Type</label>
                        <div class="btn-toolbar" role="toolbar" style="float: right">
                            <div class="btn-group" role="group">
                                <button hidden>
                                    @if (Session::has(appGlobals::getTimeCardTableName()))
                                        {!! Form::open(array('route' => array('timeCard.show', appGlobals::getIsoBeginningDowDate(Session::get(appGlobals::getTimeCardTableName()))))) !!}
                                            <input type="hidden" name="_method" value="GET">
                                            <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                            <button id="routeToTimeCardView" type="submit" class="btn btn-primary btn-xs" style="margin-right: 3px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;">
                                                <span class="glyphicon glyphicon-step-backward"></span>
                                            </button>
                                        {!! Form::close() !!}
                                    @endif
                                </button>
                                <button hidden>
                                    @if(count($tasks)>0)
                                        {{Session::put(appGlobals::getTaskTableName(), $timeCardHoursWorkedId)}}
                                        {!! Form::open(array('route' => array('taskType.task.show', $tasks[0]->TaskType->client_id, $timeCardHoursWorkedId))) !!}
                                            <input type="hidden" name="_method" value="GET">
                                            <button id="routeToTaskTypeView" type ="submit" class="btn btn-secondary btn-primary btn-xs" style="margin-right: 3px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;">
                                                <span class="glyphicon glyphicon-open"></span>
                                            </button>
                                        {!! Form::close() !!}
                                    @endif
                                </button>
                            </div>
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
                <tr class="info tbl-h2-height">
                    {!! Form::open(array('route' => array('task.create'))) !!}
                        <input type="hidden" name="_method" value="POST">
                        <input hidden type="text" name="time_card_hours_worked_id" value="{{$timeCardHoursWorkedId}}">
                        <div>
                            <th>
                                <select id="taskType" name ="taskType" class="form-control col-xs-12">
                                    <option value="0">--Select Type--</option>
                                </select>
                            </th>
                            <th><input class="form-control" id="startt-search" name="startt" placeholder="start"></th>
                            <th><input class="form-control" id="endt-search" name="endt" placeholder="end"></th>
                            <th><input disabled type="text" class="form-control" id="hoursWorked" name="hoursWorked"></th>
                            <th>
                                <div class="col-xs-8" style="display: inline-block;">
                                    <input style="padding-bottom: 0px;margin-bottom: 0px;margin-right: 0px" type="text" class="form-control" id="notes" placeholder="notes" name="notes">
                                </div>
                                <div class="btn-toolbar" role="toolbar" style="float: right">
                                    <div class="btn-group" role="group">
                                        <button hidden>
                                            <button disabled type="submit" class="btn btn-primary col-xs" id="saveButton" style="float: right;margin-left: 0px">Save</button>
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </div>
                    {!! Form::close() !!}
                </tr>
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