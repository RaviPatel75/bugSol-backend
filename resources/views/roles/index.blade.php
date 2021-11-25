@extends('layouts.app')

@section('links')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
@endsection

@section('css')
    <script>
        span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 100% !important;
        }
    </script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Role Management</h2>
        </div>
        <div class="pull-right">
        @can('role-create')
            <button class="btn btn-success create_role">Create New Role</button>
        @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif


<table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>Name</th>
     <th width="280px">Action</th>
  </tr>
    @foreach ($roles as $key => $role)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $role->name }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
            @can('role-edit')
                <a class="btn btn-primary edit_role" href="javascript:void(0)" data-id="{{ $role->id }}">Edit</a>
            @endcan
            @can('role-delete')
                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            @endcan
        </td>
    </tr>
    @endforeach
</table>


{!! $roles->render() !!}

<div class="role-form"></div>

@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script>
        $(document).on('click','.create_role', function(){
            $.get(
                "{{ route("roles.create") }}",
                function (data) {
                    $('.role-form').html(data);
                    $("#ajaxModel").modal("show");
                    $('.select2-multiple').select2({
                        width: "100%",
                        placeholder: "Select",
                        allowClear: true
                    });
                }
            );
        });

        $(document).on('click','.edit_role', function(){
            var role_id = $(this).data("id");
            $.get(
                "{{ route("roles.index") }}"+"/"+role_id+"/edit",
                function (data) {
                    $('.role-form').html(data);
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