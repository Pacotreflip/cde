<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! Form::model(Request::only('buscar'), ['method' => 'GET', 'class' => 'form-inline']) !!}
            <div class="form-group">
                {!! Form::text('buscar', null, ['class' => 'form-control input-sm', 
                    'placeholder' => 'escriba el texto a buscar...']) !!}
            </div>
            {!! Form::submit('Buscar', ['class' => 'btn btn-sm btn-primary']) !!}
        {!! Form::close() !!}
    </div>
</div>