

<div class="modal fade" id="modalValidacionAsignaciones" tabindex="-1" role="dialog" aria-labelledby="modalValidacionAsignaciones">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Validar asignaciones de área</h4>
            </div>
            <form id="formulario_valida_asignaciones" method="post" action="{{ route("cierre.validar.asignaciones") }}">
                    {{ csrf_field() }}
            <div class="modal-body">
                <div class="errores_modal">
                </div>
                <div class="errores_modal">
                </div>    
                <div class="row" >
                    <div class="col-md-12">
                        {{$area->ruta}}
                    </div>
                </div>
                <hr />
                
                    <div class="row" >
                        <div class="col-md-12" id="areas_encontradas" >
                            <table class="table table-striped table-hover" id="areas_seleccionadas">
                                <thead>
                                    <tr>
                                        <th style="width: 30px">#</th>
                                        <th>Artículo</th>
                                        <th>Unidad</th>
                                        <th>Cantidad Requerida</th>
                                        <th>Cantidad Asignada</th>
                                        <th>Validar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($area->materialesRequeridos as $articulo_requerido)
                                    <tr >
                                        <td>{{$i++ }}</td>
                                        <td>{{$articulo_requerido->material->descripcion}}</td>
                                        <td>{{$articulo_requerido->material->unidad}}</td>
                                        <td style="text-align: right">{{$articulo_requerido->cantidad_requerida}}</td>
                                        <td style="text-align: right">{{$articulo_requerido->cantidadMaterialesAsignados()}}</td>
                                        <td>
                                            @if($articulo_requerido->cantidadAsignacionesValidadas()>0)
                                            <input name="idarticulo_requerido[]" type="checkbox" value="{{$articulo_requerido->id}}" checked="checked" />
                                            <input name="idarticulo_requerido_validado[]" type="hidden" value="{{$articulo_requerido->id}}" />
                                            @else
                                            <input name="idarticulo_requerido[]" type="checkbox" value="{{$articulo_requerido->id}}" />
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" style="margin-right: 5px"></span>Cerrar</button>
                <button type="submit" id="btn_carga_areas" class="btn btn-success" ><span class="glyphicon glyphicon-ok-circle" style="margin-right: 5px"></span>Validar</button>
            </div>
            </form>
        </div>
    </div>
</div>


