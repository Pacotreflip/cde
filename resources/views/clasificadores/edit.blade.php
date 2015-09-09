@extends('layout')

@section('content')
    @include('clasificadores.partials.breadcrumb')

    <div class="row">
        <div class="col-md-6">
            <h1>Clasificador de Articulo</h1>
            <hr>

            {!! Form::model($clasificador, ['route' => ['clasificadores.update', $clasificador], 'method' => 'PATCH']) !!}
                @include('clasificadores.partials.fields')
                
                <div class="form-group">
                    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

            @include('partials.errors')

            <hr>

            {!! Form::open(['route' => ['clasificadores.delete', $clasificador], 'method' => 'DELETE']) !!}
                <div class="form-group">
                    {!! Form::submit('Borrar este clasificador', ['class' => 'btn btn-danger pull-right']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

    