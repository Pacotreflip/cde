<?php

namespace Ghi\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Autenticacion\Role;
use Ghi\Equipamiento\Autenticacion\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Ghi\Http\Requests\UserStoreRequest;
use Ghi\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $users = DB::table('igh.usuario')->select(DB::raw('idusuario, nombre'))->where('usuario_estado', '=', '2')->get();
//        return view("users.index")
//        ->with('users', $users);
//        
        return view("users.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        
	return view('users.create', [ 
            "roles"=>$roles]
                );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {

        
        $user = new User();
        $user->nombre_usuario =  $request->get('nombre_usuario');
        $user->nombre =  $request->get('nombre');
        $user->apellido_paterno =  $request->get('apellido_paterno');
        $user->apellido_materno =  $request->get('apellido_materno');
        $user->password = bcrypt($request->get("password"));
        $user->remember_token = str_random(10);
        $user->email =  $request->get("email");
        $user->save();
        
        
        
        foreach ($request->roles as $idrol){
            $rol = Role::find($idrol);
            $user->attachRole($rol);
        }
        
        return redirect()->route('users_index_path');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        return view('users.show', ["user"=>$usuario,"roles"=>$roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $arreglo_roles = array();
        foreach($user->roles as $rol){
            $arreglo_roles[] = $rol->id;
        }
	return view('users.edit', [
            "user"=>$user, 
            "roles"=>$roles,
            "arreglo_roles"=>$arreglo_roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->nombre_usuario =  $request->get('nombre_usuario');
        $user->nombre =  $request->get('nombre');
        $user->apellido_paterno =  $request->get('apellido_paterno');
        $user->apellido_materno =  $request->get('apellido_materno');
        $user->password = bcrypt($request->get("password"));
        $user->remember_token = str_random(10);
        $user->email =  $request->get("email");
        $user->save();
        
        $user->roles()->detach();
        foreach ($request->roles as $idrol){
            $rol = Role::find($idrol);
            $user->attachRole($rol);
        }
        
        return redirect()->route('users_index_path');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
	$user->delete();
	return redirect()->route('users_index_path');
    }
    
    public function getLista(Request $request) {
        $term = $request->input("term");

        $usuarios = DB::select("select idusuario, concat(nombre,' ', apaterno, ' ', amaterno) as nombre from igh.usuario "
                . "where usuario_estado = 2 and ( usuario like '%$term%' or nombre like '%$term%' or apaterno like '%$term%' or apaterno like '%$term%')");
        $return_array = null;
        foreach ($usuarios as $usuario) {
            $return_array[] = array("value" => $usuario->nombre, "id" => $usuario->idusuario);
        }
        return Response::json($return_array);
    }
    
    public function getFormRole($idusuario){
        $user = User::findOrFail($idusuario);
        $role_all = Role::all();
        $role_assigned = $user->roles;
        $role = $role_all->diff($role_assigned);
        return view('users.modal_assign_role_to_user',["idusuario"=>$idusuario,"roles_asignados"=>$role_assigned, "roles_disponibles"=>$role]);
    }
    public function removeRole(Request $request){
        $idusuario = $request->idusuario;
        $user = User::findOrFail($idusuario);
        $quitar = $request->roles_asignados;
        foreach ($quitar as $role_id){
            $role = Role::findOrFail($role_id);
            $user->roles()->detach($role);
        }
    }
    public function assignRole(Request $request){
        $idusuario = $request->idusuario;
        $user = User::findOrFail($idusuario);
        $asignar = $request->roles_disponibles;
        foreach ($asignar as $role_id){
            $role = Role::findOrFail($role_id);
            $user->attachRole($role);
        }
    }
}
