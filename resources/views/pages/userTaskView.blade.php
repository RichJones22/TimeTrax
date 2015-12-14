@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
?>

@section('content')

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">

            <thead>
                <tr style="background-color: darkgray;">
                    <th colspan="5" class="text-center">{{(new Carbon($timeCard[0]->date_worked))->toFormattedDateString()}}</th>
                </tr>
                <tr style="background-color: darkgray;">
                    <th>Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Hours Worked</th>
                    <th>Notes</th>
                </tr>
                <form method="post" action="{{ route('task.create', $taskTypeId) }}">
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
                                <button disabled type="submit" class="btn btn-primary col-xs-3" id="saveButton" style="float: right">Save</button>
                            </th>
                        </tr>
                    </div>
                </form>
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
                            <form method="post" action="{{ route('task.destroy', $task->id) }}">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button type ="submit" class = "btn btn-danger btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </form>
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