@extends('pages.userMainView')

<?php
use \App\Helpers\appGlobals;
?>


@section('content')

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color: darkgray;" class="tbl-h1-row-height">
                    @if (Session::has(appGlobals::getInfoMessageType()))
                        <th id="thAlertMessage" colspan="5" class="text-center" id="taskTypeHeader"><span style="color: brown;font-weight: bold">{{ Session::get(appGlobals::getInfoMessageType()) }}</span></th>
                        <th id="thNoAlertMessage" colspan="5" class="text-center" id="taskTypeHeader" style="display: none">Task Type Maintenance</th>
                    @else
                        <th colspan="5" class="text-center" id="taskTypeHeader">Task Type Maintenance</th>
                    @endif
                </tr>
                <tr style="background-color: darkgray;">
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Type</span>
                        @if (Session::has(appGlobals::getTaskTableName()))
                            {!! Form::open(array('route' => array('task.show', Session::get(appGlobals::getTaskTableName())))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="routeToTaskView" type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-step-backward"></span>
                                </button>
                            {!! Form::close() !!}
                        @endif
                    </th>
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Description</span>
                        @if (Session::has(appGlobals::getTaskTableName()))
                            {!! Form::open(array('route' => array('taskType.task.show', $clientId, Session::get(appGlobals::getTaskTableName())))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="taskTypeRefreshPage" type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </button>
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(array('route' => array('taskType.show', $clientId))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="taskTypeRefreshPage" type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </button>
                            {!! Form::close() !!}
                        @endif
                    </th>
                </tr>
                <tr class="info tbl-h2-height">
                    {!! Form::open(array('route' => array('taskType.create'))) !!}
                        <input type="hidden" name="_method" value="POST">
                        <input hidden type="text" name="client_id" value="{{$clientId}}">
                        <div>
                            <th><input class="form-control" id="taskType01" placeholder="type" name="taskType"></th>
                            <th>
                                <div class="col-xs-9" style="display: inline-block;">
                                    <input type="text" class="form-control" id="description" placeholder="description" name="description">
                                </div>
                                <button disabled type="submit" class="btn btn-primary col-xs" id="saveButtonTaskType" style="float: right">Save</button>
                            </th>
                        </div>
                    {!! Form::close() !!}
                </tr>
            </thead>

            <tbody id="taskTypeTable">
                @foreach ($taskTypes as $taskType)
                    <tr>
                        <td>{{ $taskType->type }}</td>
                        <td>
                            <div class="col-xs-9" style="display: inline-block;">
                                {{ $taskType->description }}
                            </div>
                            <form method="post" action="{{ route('taskType.destroy', $taskType->id) }}">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="{{ $taskType->type }}" type ="submit" class = "btn btn-danger btn-xs" style="float: right">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @unless(count($taskTypes))
            <p class="text-center">No Task Types have been added as yet</p>
        @endunless

    </div>

@stop