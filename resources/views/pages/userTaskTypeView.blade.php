{{--Task Type Maintenance View (ttv) --}}

@extends('pages.userMainView')

<?php
use \App\Helpers\appGlobals as appGlobals;
?>


@section('content')

    <div class="ttv-container">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="tbl-h1-row-height ttv-table-row-background-color">
                    @if (Session::has(appGlobals::getInfoMessageType()))
                        <th id="thAlertMessage" colspan="5" class="text-center" id="taskTypeHeader"><span style="color: brown;font-weight: bold">{{ Session::get(appGlobals::getInfoMessageType()) }}</span></th>
                        <th id="thNoAlertMessage" colspan="5" class="text-center" id="taskTypeHeader" style="display: none">Task Type Maintenance</th>
                    @else
                        <th colspan="5" class="text-center" id="taskTypeHeader">Task Type Maintenance</th>
                    @endif
                </tr>
                <tr class="ttv-table-row-background-color">
                    <th>
                        <span class="col-xs-9 ttv-inline-block">Type</span>
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
                        <span class="col-xs-9 ttv-inline-block">Description</span>
                        @if (Session::has(appGlobals::getTaskTableName()))
                            {!! Form::open(array('route' => array('taskType.task.show', $clientId, Session::get(appGlobals::getTaskTableName())))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="taskTypeRefreshPage" type ="submit" class = "btn btn-primary btn-xs pull-right">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </button>
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(array('route' => array('taskType.show', $clientId))) !!}
                                <input type="hidden" name="_method" value="GET">
                                <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                <button id="taskTypeRefreshPage" type ="submit" class = "btn btn-primary btn-xs pull-right">
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
                        <input hidden type="text" name="saveTaskType_id" value="">
                        <div>
                            <th><input class="form-control" id="taskType01" placeholder="type" name="taskType"></th>
                            <th>
                                <div class="col-xs-9 ttv-inline-block">
                                    <input type="text" class="form-control" id="description" placeholder="description" name="description">
                                </div>
                                <button disabled type="submit" class="btn btn-primary col-xs pull-right" id="saveButtonTaskType">Save</button>
                            </th>
                        </div>
                    {!! Form::close() !!}
                </tr>
            </thead>

            <tbody id="taskTypeTable">
                @foreach ($taskTypes as $taskType)
                    <tr>
                        <td class="rowTaskType">
                           <input class="taskTypeEditButton" type="text" value="{{ $taskType->type }}" readonly>
                        </td>
                        <td>
                            <div class="col-xs-9 rowTaskDesc taskTypeEditButton ttv-inline-block">
                                {{ $taskType->description }}
                            </div>
                            <div class="ttv-inline-block pull-right">
                                <div>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <button hidden>
                                                <button type="submit" class="btn btn-xs taskTypeEditButton ttv-btn-left">
                                                    <input hidden type="text" name="rowTaskType_id" value="{{$taskType->id}}">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </button>
                                            </button>
                                            <button hidden>
                                                <form method="post" action="{{ route('taskType.destroy', $taskType->id) }}">
                                                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                                                    <button type ="submit" class = "btn btn-danger btn-xs ttv-btn-right">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </button>
                                                </form>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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