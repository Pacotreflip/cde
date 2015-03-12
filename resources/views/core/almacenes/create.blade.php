@extends ('layouts.default')

@section ('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2>Nuevo Almacen</h2>

            @include ('layouts.partials.errors')

            {!! Form::open(['route' => 'almacenes.store']) !!}

                <div class="row">
                    <div class="col-sm-9">
                        <!-- Descripcion Form Input -->
                        <div class="form-group">
                            {!! Form::label('descripcion', 'Descripcion:') !!}
                            {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- Economico Form Input -->
                        <div class="form-group">
                            {!! Form::label('economico', 'No. Economico:') !!}
                            {!! Form::text('economico', null, ['class' => 'form-control', 'placeholder' => 'XXX-XXX']) !!}
                        </div>
                    </div>
                </div>

                <!-- Tipo Material Form Input -->
                <div class="form-group">
                    {!! Form::label('material', 'Tipo Material:') !!}
                    {!! Form::select('material', $materiales, null, ['class' => 'form-control']) !!}
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <!-- Categoria Form Input -->
                        <div class="form-group">
                            {!! Form::label('categoria', 'Categoria:') !!}
                            {!! Form::select('categoria', $categorias, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- Propiedad Form Input -->
                        <div class="form-group">
                            {!! Form::label('propiedad', 'Propiedad:') !!}
                            {!! Form::select('propiedad', $propiedades, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    {{--<div class="col-sm-4">--}}
                        {{--<!-- Tipo Combustible Form Input -->--}}
                        {{--<div class="form-group">--}}
                            {{--{{ Form::label('combustible', 'Combustible:') }}--}}
                            {{--{{ Form::select('combustible', ['Diesel', 'Gasolina', 'Otro'], null, ['class' => 'form-control']) }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                {{--<div class="row">--}}
                    {{--<div class="col-sm-4">--}}
                        {{--<!-- Marca Form Input -->--}}
                        {{--<div class="form-group">--}}
                            {{--{{ Form::label('marca', 'Marca:') }}--}}
                            {{--{{ Form::text('marca', null, ['class' => 'form-control']) }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-4">--}}
                        {{--<!-- Modelo Form Input -->--}}
                        {{--<div class="form-group">--}}
                            {{--{{ Form::label('modelo', 'Modelo:') }}--}}
                            {{--{{ Form::text('modelo', null, ['class' => 'form-control']) }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-4">--}}
                        {{--<!-- Placas Form Input -->--}}
                        {{--<div class="form-group">--}}
                            {{--{{ Form::label('placas', 'No. de Placas:') }}--}}
                            {{--{{ Form::text('placas', null, ['class' => 'form-control']) }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group">
                   {!! Form::submit('Registrar Almacen', ['class' => 'btn btn-primary']) !!}
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop