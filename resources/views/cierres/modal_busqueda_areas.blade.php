

<div class="modal fade" id="modalBusquedaAreas" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaAreas">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Selección de áreas para el ciere </h4>
            </div>
            <div class="modal-body">
                <div class="errores_modal">

                </div>
                <div class="errores_modal">

                    <div class="row" >
                        <div class="col-md-12"><label for="busqueda_area" >Ingrese la clave o el nombre del área que 
                                desea agregar a la transacción de cierre actual:</label>
                            
                        </div>
                    </div>



                    <div class="row" >
                        <div class="col-md-10">
                            <input name="busqueda_area" id="busqueda_area" type="text" class="form-control"  value="" />                            
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit" v-bind:disabled="asignando" @click="confirmaAsignacion">
                                <span><i class="fa fa-search"></i> Buscar</span>
                            </button>
                        </div>
                    </div>
                    <hr />
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

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" style="margin-right: 5px"></span>Cerrar</button>
                <button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-plus-sign" style="margin-right: 5px"></span>Agregar</button>
            </div>
        </div>
    </div>
</div>


