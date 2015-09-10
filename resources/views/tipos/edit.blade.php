@extends('layout')

@section('content')
    @include('tipos.partials.breadcrumb')

    <div class="row">
        <div class="col-md-6">
            <h1>Tipo de Area</h1>
            <hr>

            {!! Form::model($tipo, ['route' => ['tipos.update', $tipo], 'method' => 'PATCH']) !!}
                @include('tipos.partials.fields')
                
                <div class="form-group">
                    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

            @include('partials.errors')

            <hr>
            
            <p class="alert alert-danger">
                <i class="fa fa-fw fa-exclamation"></i><strong>Atención:</strong>
                Al borrar este tipo de area, todos los subtipos contenidos también seran borrados.
            </p>

            {!! Form::open(['route' => ['tipos.delete', $tipo], 'method' => 'DELETE']) !!}
                <div class="form-group">
                    {!! Form::submit('Borrar este tipo', ['class' => 'btn btn-danger pull-right']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

    