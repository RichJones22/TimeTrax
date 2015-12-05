@extends('pages.userMainView')

@section('content')

    <div style="margin: 40px;">
        <table class="table table-striped table-bordered">

            <thead>
                <tr style="background-color: darkgray;">
                    <th colspan="5" class="text-center">{{$timeCard[0]->date_worked}}</th>
                </tr>
                <tr style="background-color: darkgray;">
                    <th>Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Hours Worked</th>
                    <th>Notes</th>
                </tr>
                <form class="form-inline">
                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <tr class="info">
                            <th>
                                <select id="taskType">
                                    <option value="0">--Select Type--</option>
                                </select>
                            </th>
                            <th><input type="startt" class="form-control" id="startt" placeholder="start"></th>
                            <th><input type="endt" class="form-control" id="endt" placeholder="end"></th>
                            <th></th>
                            <th>
                                <div style="display: inline-block">
                                    <input type="notes" class="form-control" id="notes" placeholder="notes">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
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