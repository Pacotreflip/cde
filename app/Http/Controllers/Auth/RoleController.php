<?php

namespace Ghi\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Autenticacion\Role;
use Illuminate\Support\Facades\Validator;
use Ghi\Equipamiento\Autenticacion\Permission;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view("roles.index")
        ->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:roles|min:5|max:100',
            'display_name'=>'required|min:5|max:50',
            'description'=>'min:20|max:240',
        ],
        [
            'name.required'=>'Ingrese el nombre del rol',
            'name.unique'=>'El nombre del rol ya esta en uso',
            'name.min'=>'El nombre del rol debe tener al menos 5 caracteres',
            'name.max'=>'El nombre del rol debe tene máximo 20 caracteres',
            'display_name.required'=>'Ingrese el nombre a mostrar',
            'display_name.min'=>'El nombre a mostrar debe tener una longitud de al menos 5 caracteres',
            'display_name.max'=>'El nombre a mostrar debe tener una longitud máxima de 50 caracteres',
            'description.min'=>'La descripción debe tener una longitud de al menos 20 caracteres',
            'description.max'=>'La descripción debe tener una longitud máxima de 250 caracteres',
        ]);
        
        if($validator->fails()){
            return redirect()->route('roles_create_path')
            ->withInput()
            ->withErrors($validator);
        }
        $role = new Role();
        $role->name =  $request->get('name');
        $role->display_name =  $request->get('display_name');
        $role->description =  $request->get('description');
        
        $role->save();
        
        return redirect()->route('roles_index_path');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show', ["role"=>$role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', ["role"=>$role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|min:5|max:100',
            'display_name'=>'required|min:5|max:50',
            'description'=>'min:20|max:240',
        ],
        [
            'name.required'=>'Ingrese el nombre del rol',
            'name.min'=>'El nombre del rol debe tener al menos 5 caracteres',
            'name.max'=>'El nombre del rol debe tene máximo 20 caracteres',
            'display_name.required'=>'Ingrese el nombre a mostrar',
            'display_name.min'=>'El nombre a mostrar debe tener una longitud de al menos 5 caracteres',
            'display_name.max'=>'El nombre a mostrar debe tener una longitud máxima de 50 caracteres',
            'description.min'=>'La descripción debe tener una longitud de al menos 20 caracteres',
            'description.max'=>'La descripción debe tener una longitud máxima de 250 caracteres',
        ]);
        
        if($validator->fails()){
            return redirect()->route('roles_create_path')
            ->withInput()
            ->withErrors($validator);
        }
        $role = Role::findOrFail($id);
        $role->name =  $request->get('name');
        $role->display_name =  $request->get('display_name');
        $role->description =  $request->get('description');
        
        $role->save();
        
        return redirect()->route('roles_index_path');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
	$role->delete();
	return redirect()->route('roles_index_path');
    }
    
    public function getFormPermissions($idrole){
        $role = Role::findOrFail($idrole);
        $permissions_all = Permission::all();
        $permissions_assigned = $role->permissions;
        $permissions = $permissions_all->diff($permissions_assigned);
        $role_id = $idrole;
        
        return view('roles.modal_assign_permissions_to_role',["role_id"=>$role_id,"permisos_asignados"=>$permissions_assigned, "permisos_disponibles"=>$permissions]);
    }
    
    public function removePermissions(Request $request){
        $role_id = $request->role_id;
        $role = Role::findOrFail($role_id);
        $quitar = $request->permisos_asignados;
        foreach ($quitar as $permission_id){
            $permission = Permission::findOrFail($permission_id);
            $role->permissions()->detach($permission);
        }
    }
    public function assignPermissions(Request $request){
        $role_id = $request->role_id;
        $role = Role::findOrFail($role_id);
        $asignar = $request->permisos_disponibles;
        foreach ($asignar as $permission_id){
            $permission = Permission::findOrFail($permission_id);
            $role->attachPermission($permission);
        }
    }
}
