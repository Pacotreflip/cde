
    <input name="idusuario" id="idusuario" type="hidden"  value="{{ $idusuario  }}"/>
    <div class="modal fade" id="modal_assign_role_to_user" tabindex="-1" role="dialog" aria-labelledby="modal_assign_permissions_to_role">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-knight" style="margin-right: 5px"></span>Asiginación de Roles</h4>
                </div>
                <div class="modal-body">
                    <div class="errores">

                    </div>
                    <div class="contenido">
                         <div class="row" >
                            <div class="col-md-5">
                                Roles Asignados
                            </div>
                            <div class="col-md-2" >
                            </div>
                            <div class="col-md-5" >
                                Roles Disponibles
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-5">
                                <select multiple size="8" name="roles_asignados[]" id="roles_asignados" class="form-control">
                                    @foreach($roles_asignados as $rol_asignado)
                                    <option value="{{ $rol_asignado->id }}">
                                        {{ $rol_asignado->display_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" style="text-align: center" >
                                <button class="btn btn-success agrega_roles"><span class="glyphicon glyphicon-chevron-left" style="margin-right: 5px"></span>Asignar</button>
                                <br>
                                <br>
                                <button class="btn btn-danger quita_roles">Quitar<span class="glyphicon glyphicon-chevron-right" style="margin-left: 5px"></span></button>
                            </div>
                            <div class="col-md-5" >
                                <select multiple size="8" name="roles_disponibles[]" id="roles_disponibles" class="form-control">
                                    @foreach($roles_disponibles as $rol_disponible)
                                    <option value="{{ $rol_disponible->id }}">
                                        {{ $rol_disponible->display_name }}
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


