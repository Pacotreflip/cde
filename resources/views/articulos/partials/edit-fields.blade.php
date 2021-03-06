<!-- Descripción Form Input -->
<div class="form-group">
  {!! Form::label('descripcion', 'Descripción:') !!}
  {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
</div>

<div class="row">
  <div class="col-xs-4">
    <!-- Numero de Parte Form Input -->
    <div class="form-group">
      {!! Form::label('numero_parte', 'Numero de Parte:') !!}
      {!! Form::text('numero_parte', null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-xs-4">
    <!-- Unidad Form Input -->
    <div class="form-group">
      {!! Form::label('unidad', 'Unidad:') !!}
      {!! Form::select('unidad', $unidades, null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-xs-4">
    <!-- Unidad Form Input -->
    <div class="form-group">
      {!! Form::label('color', 'Color:') !!}
      {!! Form::text('color', null, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-6">
    <!-- Familia Form Input -->
    <div class="form-group">
      {!! Form::label('familia', 'Familia:') !!}
      {!! Form::select('familia', $familias, 
        $material->familia() ? $material->familia()->id_material : null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-xs-6">
    <!-- Clasificador Form Input -->
    <div class="form-group">
        {!! Form::label('id_clasificador', 'Clasificador:') !!}
        {!! Form::select('id_clasificador', $clasificadores, null, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>

<!-- Descripción Larga Form Input -->
<div class="form-group">
  {!! Form::label('descripcion_larga', 'Descripción Larga:') !!}
  {!! Form::textarea('descripcion_larga', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Ficha Técnica Form Input -->
<div class="form-group">
  {!! Form::label('ficha_tecnica', 'Ficha Técnica:') !!}
    @if($material->ficha_tecnica_nombre)
      <a href="/{{ $material->ficha_tecnica_path }}" target="_blank">
        <i class="fa fa-fw fa-file"></i> {{ $material->ficha_tecnica_nombre }}
      </a>
    <button type="button" class="btn btn-xs btn-danger" id="ficha-borrar" ><i class="fa fa-times"></i></button>
    @endif
  {!! Form::file('ficha_tecnica', null, ['class' => 'form-control']) !!}
</div>

<hr>
<div class="row">
  <div class="col-xs-6">
    <div class="form-group">
      {!! Form::label('precio_estimado', 'Precio Estimado:') !!}
      {!! Form::text('precio_estimado', null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-xs-6">
    <div class="form-group">
        {!! Form::label('id_moneda', 'Moneda:') !!}
        {!! Form::select('id_moneda', $monedas, null, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-6">
    <div class="form-group">
      {!! Form::label('precio_proyecto_comparativo', 'Precio Proyecto Comparativo:') !!}
      {!! Form::text('precio_proyecto_comparativo', null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-xs-6">
    <div class="form-group">
        {!! Form::label('id_moneda_proyecto_comparativo', 'Moneda Proyecto Comparativo:') !!}
        {!! Form::select('id_moneda_proyecto_comparativo', $monedas, null, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>
<div class="row">
            <div class="col-md-12">
                <h3>Datos para Reporte de Costos</h3>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-12 control-label"  >Material Secrets Correspondiente:</label>
                    {!! Form::select('id_material_secrets', $materiales_secrets, $material->id_material_secrets, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-12 control-label"  >Área de Reporte Correspondiente:</label>
                    {!! Form::select('id_area_reporte', $areas_reporte, $material->id_area_reporte, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
<hr>

<div class="form-group">
  {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>