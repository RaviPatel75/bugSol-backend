@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Project</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('project.index') }}"> Back</a>
            </div>
        </div>
    </div>


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


    {!! Form::model($project, ['method' => 'PATCH','route' => ['project.update', $project->id]]) !!}
    {{-- <form action="{{ route('project.update',$project->id) }}" method="POST"> --}}
    	{{-- @csrf --}}
        {{-- @method('PUT') --}}


         <div class="row">
		    <div class="col-xs-12 col-sm-12 col-md-12">
		        <div class="form-group">
		            <strong>Name:</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
		        </div>
                <div class="form-group">
		            <strong>Assign to Users:</strong>
                    {!! Form::select('users[]', $users,$assignedUsers, array('class' => 'form-control','multiple')) !!}
		        </div>
		    </div>
		    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
		      <button type="submit" class="btn btn-primary">Submit</button>
		    </div>
		</div>


    {{-- </form> --}}
    {!! Form::close() !!}
@endsection