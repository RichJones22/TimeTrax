@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
    use \App\Helpers\appGlobals;
?>

@section('content')

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color: darkgray;">
                    @if (Session::has(appGlobals::getInfoMessageType()))
                        <th id="thAlertMessage" colspan="5" class="text-center"><span style="color: brown;font-weight: bold">{{ Session::get(appGlobals::getInfoMessageType()) }}</span></th>
                        <th id="thNoAlertMessage" colspan="5" class="text-center" style="display: none">{{(new Carbon($timeCard[0]->date_worked))->toFormattedDateString()}}</th>
                    @else
                        <th colspan="5" class="text-center">{{(new Carbon($timeCard[0]->date_worked))->toFormattedDateString()}}</th>
                    @endif
                </tr>
                <tr style="background-color: darkgray;">
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Type</span>
                        @if(count($tasks)>0)
                            {{Session::put('from_taskView', $timeCardId)}}
                            {!! Form::open(array('route' => array('taskType.task.show', $tasks[0]->TaskType->client_id, $timeCardId))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="routeToTaskTypeView" type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-open"></span>
                                </button>
                            {!! Form::close() !!}
                        @endif
                    </th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th><span style="color: blue; font-weight: bold"> ( {{ $totalHoursWorked }} ) </span>Hours Worked</th>
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Notes</span>
                        {!! Form::open(array('route' => array('task.show', $timeCardId))) !!}
                            <input type="hidden" name="_method" value="GET">
                            <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                            <button type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                   <span class="glyphicon glyphicon-refresh"></span>
                            </button>
                        {!! Form::close() !!}
                    </th>
                </tr>
                {!! Form::open(array('route' => array('task.create', $timeCardId))) !!}
                    <input type="hidden" name="_method" value="POST">
                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <tr class="info">
                            <th>
                                <select id="taskType" name ="taskType" class="form-control col-xs-12">
                                    <option value="0">--Select Type--</option>
                                </select>
                            </th>
                            <th><input class="form-control" id="startt-search" name="startt" placeholder="start"></th>
                            <th><input class="form-control" id="endt" name="endt" placeholder="end"></th>
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
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
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