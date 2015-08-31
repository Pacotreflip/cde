@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('tipos.index') }}">Tipos de Area</a></li>
        <li class="active">{{ $tipo->nombre }}</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <h1>Tipo de Area</h1>
            <hr>

            {!! Form::model($tipo, ['route' => ['tipos.update', $tipo], 'method' => 'PATCH']) !!}
                @include('tipos.partials.fields')
                <hr>

                <div class="form-group">
                    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

            @include('partials.errors')
            <br>
            <hr>
            <br>
            <h3>
                <a href="{{ route('subtipos.create', [$tipo]) }}" class="btn btn-sm btn-success pull-right">
                    <i class="fa fa-plus"></i> Agregar Subtipo
                </a>
                Subtipos de Area Relacionados
            </h3>

            <ul class="list-group">
                @foreach($tipo->subtipos as $subtipo)
                    <a href="{{ route('subtipos.edit', [$tipo, $subtipo]) }}" class="list-group-item">{{ $subtipo->nombre }}</a>
                @endforeach
            </ul>

            <br>
            <hr>
            {!! Form::open(['route' => ['tipos.delete', $tipo], 'method' => 'DELETE']) !!}
                <div class="form-group">
                    {!! Form::submit('Borrar este tipo de area', ['class' => 'btn btn-sm btn-danger pull-right']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop