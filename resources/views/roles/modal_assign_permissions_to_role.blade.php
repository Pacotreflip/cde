
    <input name="role_id" id="role_id" type="hidden"  value="{{ $role_id  }}"/>
    <div class="modal fade" id="modal_assign_permissions_to_role" tabindex="-1" role="dialog" aria-labelledby="modal_assign_permissions_to_role">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-knight" style="margin-right: 5px"></span>Asiginación de Permisos</h4>
                </div>
                <div class="modal-body">
                    <div class="errores">

                    </div>
                    <div class="contenido">
                         <div class="row" >
                            <div class="col-md-5">
                                Permisos Asignados
                            </div>
                            <div class="col-md-2" >
                            </div>
                            <div class="col-md-5" >
                                Permisos Disponibles
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-5">
                                <select multiple size="8" name="permisos_asignados[]" id="permisos_asignados" class="form-control">
                                    @foreach($permisos_asignados as $permiso_asignado)
                                    <option value="{{ $permiso_asignado->id }}">
                                        {{ $permiso_asignado->display_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" style="text-align: center" >
                                <button class="btn btn-success agrega_permisos"><span class="glyphicon glyphicon-chevron-left" style="margin-right: 5px"></span>Asignar</button>
                                <br>
                                <br>
                                <button class="btn btn-danger quita_permisos">Quitar<span class="glyphicon glyphicon-chevron-right" style="margin-left: 5px"></span></button>
                            </div>
                            <div class="col-md-5" >
                                <select multiple size="8" name="permisos_disponibles[]" id="permisos_disponibles" class="form-control">
                                    @foreach($permisos_disponibles as $permiso_disponible)
                                    <option value="{{ $permiso_disponible->id }}">
                                        {{ $permiso_disponible->display_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" style="margin-right: 5px"></span>Cerrar</button>
                </div>
            </div>
        </div>
    </div>


