<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Create a new project</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(array('route' => 'project.store','method'=>'POST')) !!}

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control name')) !!}
                        </div>
                        <div class="form-group">
                            <strong>Assign to Users:</strong>
                            {!! Form::select('users[]', $users,[], array('class' => 'form-control users select2-multiple','multiple')) !!}
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

