@extends('layouts.app')

@section('links')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
@endsection

@section('css')
    <style>
        .content {
            z-index: 1;
            border: 1px solid #ccc;
            background: #eee;
            position: relative;
            display: inline-block;
            box-shadow: 5px 5px 15px 1px rgba(0, 0, 0, .3);
        }

        .task-grid {
            border-collapse: separate !important;
            border: 0px solid #ccc;
            border-spacing: 0px;
            background: white;
            font-family: arial;
            width: 1000px;
            float: left;
            margin: 0px 5px;
        }

        .task-grid th {
            background-color: rgb(0, 114, 198);
            font-weight: lighter;
            color: white;
            border-left: 1px solid #cccccc;
            border-bottom: 1px solid #cccccc;
            transition: all 0.2s;
            padding: 10px;
            font-size: 16px;
        }

        .task-grid th:first-child {
            border-left: 1px;
            width: 200px;
        }

        .task-grid tr:last-child {
            border-bottom: 0px;
        }

        .task-grid tr:hover,
        .task-grid tr:hover td:first-child {
            background-color: rgb(0, 114, 198);
            transition: all 0.4s;
        }

        .task-grid td {
            border-right: 1px solid #cccccc;
            padding: 10px;
            transition: all 0.2s;
            vertical-align: top;
            border-bottom: 1px solid #cccccc;
            min-width: 220px;
        }

        .task-grid td:first-child {
            width: 100px;
            background-color: rgb(80, 80, 80);
            font-weight: lighter;
            color: white;
            padding: 10px;
            font-size: 12px;
            border-top: 0px;
            min-width: 100px;
        }

        .task-grid td:last-child {
            border-right: 1px solid #ccc;
        }

        .task-item {
            width: 100%;
            height: 22px;
            font: 11px 'Lucida Sans', 'trebuchet MS', Arial, Helvetica;
            background: #fff;
            border: 1px solid #ddd;
            color: #333;
            position: relative;
            box-shadow: 0 2px 2px 0px rgba(0, 0, 0, .5);
            margin-bottom: 5px;
            cursor: move;
        }

        .task-item:hover {
            background-color: #00C79C;
            border-color: #00C79C;
            color: #fff;
        }

        .task-item:first-child {
            margin-top: 1px;
        }

        .task-item .hours {
            width: 35px;
            height: 18px;
            float: right;
            background-color: #FF4242;
            text-align: center;
            border-bottom-left-radius: 6px;
            margin-right: -1px;
            color: white;
        }

        .task-item .hours div {
            box-sizing: border-box;
            height: 25px;
            padding-top: 3px;
            font-size: 10px;
        }

        .task-item .title {
            font-size: 12px;
            height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 3px 0px 3px 5px;
            display: block;
            line-height: 16px;
            max-width: 200px;
        }

        .task-grid-container {
            overflow: auto;
        }

        .delete-container {
            width: 240px;
            height: 60px;
            padding-top: 10px;
            padding-bottom: 10px;
            border: 1px solid #cccccc;
            background-color: #ececec;
            font-family: arial;
            font-size: 12px;
            font-weight: lighter;
            color: #000;
            clear: both;
            margin: 10px 30px;
            display: inline-block;
        }

        .completed {
            width: 240px;
            height: 60px;
            padding-top: 10px;
            padding-bottom: 10px;
            border: 1px solid #cccccc;
            background-color: #ececec;
            font-family: arial;
            font-size: 12px;
            font-weight: lighter;
            color: #000;
            clear: both;
            margin: 10px 30px;
            display: inline-block;
        }
    </style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @can('project-create')
                <a class="btn btn-success" href="javascript:void(0)" id="addTask"> Add New Task</a>
            @endcan
            {{-- @can('project-edit')
            <a class="btn btn-success" href="{{ route('project_access.index') }}"> Assign user to the project</a>
            @endcan --}}
        </div>
    </div>
</div>

<div class="task-grid-container">
    <table class="task-grid">
        <tbody>
            <tr class="grid-row header">
                <th>Not Started</th>
                <th>In Progress</th>
                <th>Unit Testing</th>
                <th>Completed</th>
            </tr>
            <tr class="grid-row">
                <td class="grid-column tasks ui-sortable foo0" data-id='0'>
                    @foreach ($kanbanData['notStarted'] as $data)
                        <div class="task-item" data-id="{{ $data->id }}">
                            <div class="title" title="{{ $data->title }}" data-oldStatus="{{ $data->status }}" data-id="{{ $data->id }}">
                                {{ $data->title }}
                            </div>
                        </div>
                    @endforeach
                </td>
                <td class="grid-column tasks ui-sortable foo1" data-id='1'>
                    @foreach ($kanbanData['inProgress'] as $data)
                        <div class="task-item" data-id="{{ $data->id }}">
                            <div class="title" title="{{ $data->title }}" data-oldStatus="{{ $data->status }}" data-id="{{ $data->id }}">
                                {{ $data->title }}
                            </div>
                        </div>
                    @endforeach
                </td>
                <td class="grid-column tasks ui-sortable foo2" data-id='2'>
                    @foreach ($kanbanData['unitTesting'] as $data)
                        <div class="task-item" data-id="{{ $data->id }}">
                            <div class="title" title="{{ $data->title }}" data-oldStatus="{{ $data->status }}" data-id="{{ $data->id }}">
                                {{ $data->title }}
                            </div>
                        </div>
                    @endforeach
                </td>
                <td class="grid-column tasks ui-sortable foo3" data-id='3'>
                    @foreach ($kanbanData['completed'] as $data)
                        <div class="task-item" data-id="{{ $data->id }}">
                            <div class="title" title="{{ $data->title }}" data-oldStatus="{{ $data->status }}" data-id="{{ $data->id }}">
                                {{ $data->title }}
                            </div>
                        </div>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>


</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>

            <div class="modal-body">
                <form id="addTaskForm" name="addTaskForm" class="form-horizontal">

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" required="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-12">
                            <select name="status" id="status" class="form-control">
                                <option value="0">Not Started</option>
                                <option value="1">In Progress</option>
                                <option value="2">Unit Testing</option>
                                <option value="3">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(document).ready(TaskListInitialize);
    
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    function TaskListInitialize() {
        $(".grid-column").sortable({
            connectWith: ".grid-column,.delete-container",
            receive: function(e, ui) {
                var previous_status = ui.sender.attr('data-id');
                var current_status = ui.item.parent().attr('data-id');
                var task_id = ui.item.attr('data-id');
                
                $.ajax({
                    data: {
                        'previous' : previous_status,
                        'current' : current_status,
                        'task_id' : task_id
                    },
                    url: "{{ route('updateStatus') }}",
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        alert(data.success);
                    }
                });
            },
            stop: function (e, ui) {
                
            }
        });
        /*$(".grid-column").draggable();
        $(".delete-container").droppable({
            accept: ".task-item"
        });*/
    }

    $("#addTask").click(function () {
        $("#ajaxModel").modal("show");
    });
    
    

    $("#saveBtn").click(function (e) {
        e.preventDefault();

        var project_id = $(location).attr("href").split('/').pop();
        $("<input type='hidden' value='' />")
            .attr("id", "project_id")
            .attr("name", "project_id")
            .attr("value", project_id)
            .prependTo("#addTaskForm");
        
        $.ajax({
            data: $("#addTaskForm").serialize(),
            url: "{{ route('kanban.store') }}",
            type: "POST",
            dataType: "json",
            success: function (data) {
                $("#addTaskForm").trigger("reset");
                $("#ajaxModel").modal("hide");
                location.reload();
            },

            error: function (data) {
                console.log("Error:", data);
                $("#saveBtn").html("Add");
            },
        });
    });
</script>
@endsection