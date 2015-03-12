@extends ('app')

@section ('content')
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<h1>Inicie Sesión</h1>

			@include ('partials.errors')

			{!! Form::open() !!}

                <!-- Usuario Form Input -->
                <div class="form-group">
                    {!! Form::label('usuario', 'Usuario:') !!}
                    {!! Form::text('usuario', null, ['class' => 'form-control', 'required', 'autofocus']) !!}
                </div>

                <!-- Password Form Input -->
                <div class="form-group">
                    {!! Form::label('clave', 'Clave:') !!}
                    {!! Form::password('clave', ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Iniciar sesión', ['class' => 'btn btn-primary']) !!}
                </div>

			{!! Form::close() !!}
		</div>
	</div>
@endsection