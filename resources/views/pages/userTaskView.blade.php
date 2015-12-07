@extends('pages.userMainView')

<?php
    use \Carbon\Carbon;
?>

@section('content')

    <div style="margin: 40px;">
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
                <form>
                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <tr class="info">
                            <th>
                                <select id="taskType" class="form-control col-xs-12">
                                    <option value="0">--Select Type--</option>
                                </select>
                            </th>
                            <th><input class="form-control" id="startt-search" placeholder="start"></th>
                            <th><input class="form-control" id="endt" placeholder="end"></th>
                            <th><input disabled type="text" class="form-control" id="hoursWorked"></th>
                            <th>
                                <div class="col-xs-9" style="display: inline-block;">
                                    <input type="notes" class="form-control" id="notes" placeholder="notes">
                                </div>
                                <button type="submit" class="btn btn-primary col-xs-3" style="float: right">Save</button>
                            </th>
                        </tr>
                    </div>
                </form>
            </thead>

            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->TaskType->type }}</td>
                        <td>{{ $task->start_time }}</td>
                        <td>{{ $task->end_time }}</td>
                        <td>{{ $task->hours_worked }}</td>
                        <td>{{ $task->notes }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        @unless(count($tasks))
            <p class="text-center">No Tasks have been added as yet</p>
        @endunless
    </div>

@stop