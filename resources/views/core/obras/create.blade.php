@extends ('layouts.default')

@section ('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <h1>Nueva Obra</h1>

            @include ('layouts.partials.errors')

            {!! Form::open(['route' => 'obras.store']) !!}
                <fieldset>
                    <legend>Identificación</legend>
                    <div class="row">
                        <div class="col-sm-4">
                            <!-- Base Datos Form Input -->
                            <div class="form-group">
                                {!! Form::label('connection', 'Base Datos:') !!}
                                {!! Form::select('connection', $connections, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <!-- Nombre Form Input -->
                            <div class="form-group">
                                {!! Form::label('nombre', 'Nombre:') !!}
                                {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <!-- Descripcion Form Input -->
                    <div class="form-group">
                        {!! Form::label('descripcion', 'Descripcion:') !!}
                        {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                    <!-- Estado Form Input -->
                    <div class="form-group">
                        {!! Form::label('estadoObra', 'Estado:') !!}
                        {!! Form::select('estadoObra', ['En Ejecucion', 'En Proyecto', 'Terminada'], null, ['class' => 'form-control']) !!}
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contrato</legend>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Constructora Form Input -->
                            <div class="form-group">
                                {!! Form::label('constructora', 'Constructora:') !!}
                                {!! Form::text('constructora', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- Cliente Form Input -->
                            <div class="form-group">
                                {!! Form::label('cliente', 'Cliente:') !!}
                                {!! Form::text('cliente', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Facturar Form Input -->
                            <div class="form-group">
                                {!! Form::label('facturar', 'Facturar a:') !!}
                                {!! Form::text('facturar', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- Rfc Form Input -->
                            <div class="form-group">
                                {!! Form::label('rfc', 'Rfc:') !!}
                                {!! Form::text('rfc', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- Responsable Form Input -->
                            <div class="form-group">
                                {!! Form::label('responsable', 'Responsable:') !!}
                                {!! Form::text('responsable', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Localización</legend>

                    <!-- Direccion Form Input -->
                    <div class="form-group">
                        {!! Form::label('direccion', 'Direccion:') !!}
                        {!! Form::textarea('direccion', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Estado Form Input -->
                            <div class="form-group">
                                {!! Form::label('estado', 'Estado:') !!}
                                {!! Form::text('estado', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- Ciudad Form Input -->
                            <div class="form-group">
                                {!! Form::label('ciudad', 'Ciudad:') !!}
                                {!! Form::text('ciudad', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- Codigo_postal Form Input -->
                            <div class="form-group">
                                {!! Form::label('codigoPostal', 'Codigo Postal:') !!}
                                {!! Form::text('codigoPostal', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Información Financiera</legend>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Moneda Form Input -->
                            <div class="form-group">
                                {!! Form::label('moneda', 'Moneda:') !!}
                                {!! Form::select('moneda', [1 => 'PESOS', 2 => 'USD', 3 => 'EUROS'], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- Iva Form Input -->
                            <div class="form-group">
                                {!! Form::label('iva', 'Porcentaje de Iva:') !!}
                                {!! Form::text('iva', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Calendario</legend>

                    <div class="row">
                        <div class="col-xs-6">
                            <!-- Fecha Inicial Form Input -->
                            <div class="form-group">
                                {!! Form::label('fechaInicial', 'Fecha Inicial:') !!}
                                {!! Form::text('fechaInicial', null, ['class' => 'form-control', 'placeholder' => 'aaaa-mm-dd']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <!-- Fecha Inicial Form Input -->
                            <div class="form-group">
                                {!! Form::label('fechaFinal', 'Fecha Final:') !!}
                                {!! Form::text('fechaFinal', null, ['class' => 'form-control', 'placeholder' => 'aaaa-mm-dd']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    {!! Form::submit('Registrar Obra', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
