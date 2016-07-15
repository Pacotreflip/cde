<div id="entregas_programadas_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Entregas Programadas</h4>
      </div>
        <div class="modal-body" id="modal-body">
          <h4>{{ $item->material->descripcion }}</h4>
          <hr>
          <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Total Adquirido: <strong id="cantidad">{{ $item->cantidad }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Total Programado: <strong id="totalProgramado">{{ $item->totalProgramado() }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-4 col-xs-4 text-center">
                <p>Faltante: <strong id="faltante">{{ $item->cantidad - $item->totalProgramado() }}</strong></p>                  
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 text-center">
                <button class="btn btn-success pull-right" onclick="agregar('{{ $item->id_item }}')"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
          <div class="table-responsive">
              <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>Fecha de Entrega</th>
                          <th>Cantidad</th>
                          <th>Registró</th>
                          <th>Fecha de Registro</th>
                          <th></th>
                      </tr>
                  </thead>    
                  <tbody>
                      @foreach($item->entregasProgramadas as $entrega_programada)
                      <tr>
                          <td>{{ $entrega_programada->fecha_entrega->format('d-m-Y') }}</td>
                          <td>{{ $entrega_programada->cantidad_programada }}</td>
                          <td>{{ $entrega_programada->usuario_registro->present()->nombreCompleto }}</td>
                          <td>{{ $entrega_programada->created_at->format('d-m-Y h:i:s') }}</td>
                          <td class="text-center">
                              <button class="btn btn-xs btn-danger" onclick="borrar('{{$entrega_programada->id}}', this)"><i class="fa fa-times"></i></button>
                              <button class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>
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