<div id="modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pagos Programados</h4>
      </div>
        <div class="modal-body" id="modal-body">
          <h4>Orden de Compra {{$compra->numero_folio }}</h4>
          <hr>
          <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Total: <strong id="cantidad">{{ round($compra->monto, 2) }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Total Programado: <strong id="totalProgramado">{{ $compra->totalProgramado() }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Faltante: <strong id="faltante">{{ round($compra->monto, 2) - $compra->totalProgramado() }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 text-center">
                <button class="btn btn-success pull-right" onclick="agregarPago()"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
          <div class="table-responsive">
              <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>Fecha de Pago</th>
                          <th>Monto</th>
                          <th>Registró</th>
                          <th>Fecha de Registro</th>
                          <th></th>
                      </tr>
                  </thead>    
                  <tbody>
                      @foreach($compra->pagosProgramados as $pago)
                      <tr>
                          <td>{{ $pago->fecha->format('d-m-Y') }}</td>
                          <td>{{ $pago->monto }}</td>
                          <td>{{ $pago->usuario_registro->present()->nombreCompleto }}</td>
                          <td>{{ $pago->created_at->format('d-m-Y h:i:s') }}</td>
                          <td class="text-center">
                              <button class="btn btn-xs btn-danger" onclick="borrarPago('{{$compra->id_transaccion}}', '{{$pago->id}}', this)"><i class="fa fa-times"></i></button>
                              <button class="btn btn-xs btn-info" onclick="editarPago('{{$compra->id_transaccion}}', '{{$pago->id}}')"><i class="fa fa-pencil"></i></button>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>    
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->