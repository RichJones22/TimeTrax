@extends('pages.userMainView')

@section('content')

    <div style="margin: 60px;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color: darkgray;">
                    <th colspan="5" class="text-center" id="taskTypeHeader">Task Type Maintenance</th>
                </tr>
                <tr style="background-color: darkgray;">
                    <th id="taskTypeId">Type</th>
                    <th>
                        <span class="col-xs-9" style="display: inline-block;">Description</span>
                        <form method="get" action="{{ route('taskType.show', $clientId) }}">
                            <button type ="submit" class = "btn btn-primary btn-xs" style="float: right">
                                   <span class="glyphicon glyphicon-refresh"></span>
                            </button>
                        </form>
                    </th>
                </tr>
                <form method="post" action="{{ route('taskType.create', $clientId) }}">
                    <input hidden type="text" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <tr class="info">
                            <th><input class="form-control" id="taskType" placeholder="type" name="taskType"></th>
                            <th>
                                <div class="col-xs-9" style="display: inline-block;">
                                    <input type="text" class="form-control" id="description" placeholder="description" name="description">
                                </div>
                                <button disabled type="submit" class="btn btn-primary col-xs-3" id="saveButtonTaskType" style="float: right">Save</button>
                            </th>
                        </tr>
                    </div>
                </form>
            </thead>

            <tbody id="taskTypeTable">
                @foreach ($taskTypes as $taskType)
                    <tr>
                        <td>{{ $taskType->type }}</td>
                        <td>
                            <div class="col-xs-9" style="display: inline-block;">
                                {{ $taskType->description }}
                            </div>
                            <form method="post" action="{{ route('taskType.destroy', $clientId) }}">
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

        @unless(count($taskTypes))
            <p class="text-center">No Task Types have been added as yet</p>
        @endunless

    </div>

@stop