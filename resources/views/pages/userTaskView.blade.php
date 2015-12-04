<?php
    use \App\Helpers\appGlobals;
?>

@extends('pages.userMainView')

@section('content')

    <h1 class="page-heading">{{ucfirst(appGlobals::getTaskTableName())}} View for {{$timeCard[0]->date_worked}}</h1>

    <hr>

    <table class="table table-striped table-bordered">
        <head>
            <th>Type</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Hours Worked</th>
            <th>Notes</th>
        </head>

        <body>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->TaskType->type }}</td>
                <td>{{ $task->start_time }}</td>
                <td>{{ $task->end_time }}</td>
                <td>{{ $task->hours_worked }}</td>
                <td>{{ $task->notes }}</td>
            </tr>
        @endforeach
        </body>
    </table>

    @unless(count($tasks))
        <p class="text-center">No Tasks have been added as yet</p>
    @endunless

@stop