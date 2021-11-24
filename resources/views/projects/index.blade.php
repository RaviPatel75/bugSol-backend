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
                <h2>Projects</h2>
            </div>
            <div class="pull-right">
                @can('project-create')
                <button class="btn btn-success create_project">Create New Project</button>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="300px">Action</th>
        </tr>
	    @foreach ($projects as $project)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $project->name }}</td>
	        <td>
                <form action="{{ route('project.destroy',$project->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('project.show',$project->id) }}">Show</a>
                    <a class="btn btn-info" href="{{ route('kanban.show',$project->id) }}">Kanban</a>
                    @can('project-edit')
                    <a class="btn btn-primary" href="{{ route('project.edit',$project->id) }}">Edit</a>
                    @endcan


                    @csrf
                    @method('DELETE')
                    @can('project-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan
                </form>
	        </td>
	    </tr>
	    @endforeach
    </table>


    {!! $projects->links() !!}

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
    
                <div class="modal-body">
                    {!! Form::open(array('route' => 'project.store','method'=>'POST')) !!}
    
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <strong>Assign to Users:</strong>
                                {!! Form::select('users[]', $users,[], array('class' => 'form-control','multiple')) !!}
                            </div>
                            
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $(document).on('click','.create_project', function(){
            $("#ajaxModel").modal("show");
        });
    </script>
@endsection