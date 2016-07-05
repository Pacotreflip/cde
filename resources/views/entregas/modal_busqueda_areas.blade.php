

<div class="modal fade" id="modalBusquedaAreas" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaAreas">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Selección de áreas para entrega </h4>
            </div>
            <div class="modal-body">
                <div class="errores_modal">

                </div>
                <div class="errores_modal">

                    <div class="row" >
                        <div class="col-md-12"><label for="busqueda_area" >Ingrese la clave o el nombre del área que 
                                desea agregar a la transacción de entrega actual:</label>
                            
                        </div>
                    </div>


                <form id="formulario_busqueda_area" method="post" action="{{ route("entrega.get.areas") }}">
                {{ csrf_field() }}
                @if(is_array($ids_areas))
                    @foreach($ids_areas as $id_areas)
                    <input type="hidden" name="id_area[]" value="{{$id_areas}}" />
                    @endforeach
                @endif
                    <div class="row" >
                        <div class="col-md-10">
                            <input name="busqueda_area" id="busqueda_area" type="text" class="form-control"  value="" />                            
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" id="busca_area" type="submit">
                                <span><i class="fa fa-search"></i> Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>
                    <hr />
                    <form id="formulario_carga_areas" method="post" action="{{ route("entrega.create.areas") }}">
                {{ csrf_field() }}
                <input type="hidden" name="fecha_entrega" value="{{$fecha_entrega}}" />
                <input type="hidden" name="concepto" value="{{$concepto}}" />
                <input type="hidden" name="entrega" value="{{$entrega}}" />
                <input type="hidden" name="recibe" value="{{$recibe}}" />
                <input type="hidden" name="observaciones" value="{{$observaciones}}" />
                @if(is_array($ids_areas))
                    @foreach($ids_areas as $id_areas)
                    <input type="hidden" name="id_area[]" value="{{$id_areas}}" />
                    @endforeach
                @endif
                <div class="row">
                    <div class="col-md-12" id="error_areas_encontradas" style="display: none">
                        <div class="alert alert-danger" role="alert">
                        No se encontraron áreas con el parametro de búsqueda ingresado.
                        </div>
                    </div>
                </div>
                    <div class="row" >
                        <div class="col-md-12" id="areas_encontradas" style="display: none">
                            <table class="table table-striped table-hover" id="areas_seleccionadas">
                <thead>
                    <tr>
                        <th style="width: 30px"></th>
                        <th>Clave</th>
                        <th>Área</th>
                        <th>Artículos Requeridos</th>
                        <th>Artículos Asignados</th>
                        <th>Artículos Validados</th>
                    </tr>
                </thead>
            <tbody>
                <tr class="template" style="display: none">
                    <td><span class="id_area"></span></td>
                    <td><span class="clave"></span></td>
                    <td><span class="area"></span></td>
                    <td><span class="articulos_requeridos" style="text-align: right"></span></td>
                    <td><span class="articulos_asignados" style="text-align: right"></span></td>
                    <td><span class="articulos_validados" style="text-align: right"></span></td>
                </tr>
            </tbody>
            </table>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" style="margin-right: 5px"></span>Cerrar</button>
                <button type="button" id="btn_carga_areas" class="btn btn-success" ><span class="glyphicon glyphicon-plus-sign" style="margin-right: 5px"></span>Agregar</button>
            </div>
        </div>
    </div>
</div>


