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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $users = User::all();
//        return view("users.index")
//        ->with('users', $users);
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
        //$usuarios = User::where('nombre', 'like', '%' . $term . "%")->get();
        $usuarios = DB::table('usuario')->select(DB::raw('idusuario, nombre'))->where('nombre', 'like', '%' . $term . "%")->get();
        
//        $return_array = null;
//        foreach ($usuarios as $usuario) {
//            $return_array[] = array("value" => $usuario->nombre, "id" => $usuarios->idusuario);
//        }
//        return Response::json($return_array);
    }
}
