<div class="box">
    <div class="box-header">

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($__singular_var__, ['route' => [config('laraadmin.adminRoute') . '.__db_table_name__.update', $__singular_var__->id ], 'method'=>'PUT', 'id' => '__singular_var__-edit-form']) !!}


                __input_fields__

                <br>
                <div class="form-group">
                    {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/__db_table_name__') }}" class="btn btn-default pull-right">Cancel</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
