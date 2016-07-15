<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Entregas Programadas</h4>
    </div> 
      
    <form id="entrega_programada_form">
    <input type="hidden" name="faltante" value="{{ $item->cantidad - $item->totalProgramado() }}">
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
            <input type="date" class="form-control" id="fecha_entrega" value="{{ $fecha_entrega }}" name="fecha_entrega">
          </div>
          <div class="form-group">
            <label for="Cantidad">Cantidad:</label>
            <input type="number" class="form-control" id="cantidad" value="{{ $item->cantidad - $item->totalProgramado() }}" name="cantidad">
          </div>
        </div>
        <div class="col-md-6 col-sm-12"> 
          <div class="form-group">
            <label for="Observaciones">Observaciones:</label>
            <textarea form="entrega_programada_form" type="text" class="form-control" id="observaciones" name="observaciones" max="200"></textarea>
          </div>
        </div>
      </div>
      <div class="row" id="errores">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-success" id="crear_entrega_programada" onclick="store('{{ $item->id_item }}')">Guardar</button>
      <button type="button" class="btn btn-primary" onclick="showModal('{{ route('entregas_programadas.index', ['id_item' => $item->id_item]) }}')" data-dismiss="modal">Regresar</button>
    </div>
        
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->