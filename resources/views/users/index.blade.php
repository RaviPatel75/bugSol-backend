@extends('layouts.app')

@section('links')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
            <div class="pull-right">
                <button class="btn btn-success create_user">Create New User</button>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered data-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th width="280px">Action</th>
        </tr>
    </table>


    {!! $data->render() !!}

    <div class="user-form"></div>

@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script>
         $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $(function(){
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: "{{ route('getUsers') }}",
                columns: [
                    { data: "name", name: "name" },
                    { data: "email", name: "email" },
                    { data: "role", name: "role " },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        });
        $(document).on('click','.delete_user',function(){
                var id = $(this).data('id');
                if (confirm('Really delete?')) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _method: 'DELETE'
                        },
                        url: "users/" + id,
                        success: function (data) {
                            location.reload();
                        } 
                    });
                }
            });
        $(document).on('click','.create_user', function(){
            $.get(
                "{{ route("users.create") }}",
                function (data) {
                    $('.user-form').html(data);
                    $("#ajaxModel").modal("show");
                    $('.select2-multiple').select2({
                        width: "100%",
                        placeholder: "Select",
                        allowClear: true
                    });
                }
            );
        });
        $(document).on('click','.edit_user', function(){
            var user_id = $(this).data("id");
            $.get(
                "{{ route("users.index") }}"+"/"+user_id+"/edit",
                function (data) {
                    $('.user-form').html(data);
                    $("#ajaxModel").modal("show");
                    $('.select2-multiple').select2({
                        width: "100%",
                        placeholder: "Select",
                        allowClear: true
                    });
                }
            );
        });
    </script>

@endsection