<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Pagos Programados</h4>
    </div> 
    <form id="pago_programado_form">
    <input type="hidden" name="faltante" value="{{ round($compra->monto, 2) - $compra->totalProgramado() }}">
    <div class="modal-body" id="modal-body">
      <h4>Orden de Compra {{ $compra->numero_folio }}</h4>
      <hr>
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Total: <strong id="cantidad">{{ round($compra->monto, 2) }}</strong></p>                  
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Total Programado: <strong id="totalProgramado">{{ $compra->totalProgramado() }}</strong></p>                  
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
          <p>Faltante: <strong id="faltante">{{ round($compra->monto - $compra->totalProgramado(), 2) }}</strong></p>                  
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-sm-12">    
          <div class="form-group">
            <label for="fecha_entrega">Fecha de Pago:</label>
            <input type="date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" name="fecha">
          </div>
          <div class="form-group">
            <label for="Cantidad">Monto:</label>
            <input type="number" class="form-control" value="{{ (round($compra->monto, 2) - $compra->totalProgramado()) }}" name="monto">
          </div>
        </div>
        <div class="col-md-6 col-sm-12"> 
          <div class="form-group">
            <label for="Observaciones">Observaciones:</label>
            <textarea form="pago_programado_form" type="text" class="form-control" name="observaciones" max="200"></textarea>
          </div>
        </div>
      </div>
      <div class="row" id="errores">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-success" id="crear_entrega_programada" onclick="storePago('{{ $compra->id_transaccion }}')">Guardar</button>
      <button type="button" class="btn btn-primary" onclick="showModal('{{ route('compra.pagos_programados.index', $compra) }}')" data-dismiss="modal">Regresar</button>
    </div>
        
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->