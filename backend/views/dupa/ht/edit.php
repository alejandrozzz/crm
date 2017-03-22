<div class="box">
    <div class="box-header">

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($ht, ['route' => [config('laraadmin.adminRoute') . '.ht.update', $ht->id ], 'method'=>'PUT', 'id' => 'ht-edit-form']) !!}
                @la_form($module)

                {{--
                <input type="text" name="a" value="">
					 <input type="text" name="b" value="">
                --}}
                <br>
                <div class="form-group">
                    {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/ht') }}" class="btn btn-default pull-right">Cancel</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
