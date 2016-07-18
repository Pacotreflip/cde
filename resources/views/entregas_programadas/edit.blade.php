<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Entregas Programadas</h4>
    </div> 
      
    <form id="edit_entrega_programada_form">
    <input type="hidden" name="faltante" value="{{ $item->cantidad - $item->totalProgramado() }}">
    <input type="hidden" name="actual" value="{{ $entregaprogramada->cantidad_programada }}">
    <div class="modal-body" id="modal-body">
      <h4>{{ $item->material->descripcion }}</h4>
      <hr>
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Total Adquirido: <strong id="cantidad">{{ $item->cantidad }}</strong></p>                  
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Total Programado: <strong id="totalProgramado">{{ $item->totalProgramado() }}</strong></p>                  
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Faltante: <strong id="faltante">{{ $item->cantidad - $item->totalProgramado() }}</strong></p>                  
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-sm-12">    
          <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" class="form-control" value="{{ $entregaprogramada->fecha_entrega->format('Y-m-d') }}" name="fecha_entrega">
          </div>
          <div class="form-group">
            <label for="Cantidad">Cantidad:</label>
            <input type="number" class="form-control" value="{{ intval($entregaprogramada->cantidad_programada) }}" name="cantidad">
          </div>
        </div>
        <div class="col-md-6 col-sm-12"> 
          <div class="form-group">
            <label for="Observaciones">Observaciones:</label>
            <textarea form="edit_entrega_programada_form" type="text" class="form-control" name="observaciones" max="200">{{ $entregaprogramada->observaciones }}</textarea>
          </div>
        </div>
      </div>
      <div class="row" id="errores">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-success" onclick="update('{{ $entregaprogramada->id }}', '{{ $item->id_item }}')">Actualizar</button>
      <button type="button" class="btn btn-primary" onclick="showModal('{{ route('entregas_programadas.index', ['id_item' => $item->id_item]) }}')" data-dismiss="modal">Regresar</button>
    </div>
        
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->