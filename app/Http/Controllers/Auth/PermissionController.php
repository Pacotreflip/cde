<?php

namespace Ghi\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Illuminate\Support\Facades\Validator;

use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Autenticacion\Permission;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view("permissions.index")
        ->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
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
            'name'=>'required|unique:permissions|min:5|max:100',
            'display_name'=>'required|min:10|max:50',
            'description'=>'min:20|max:240',
        ],
        [
            'name.required'=>'Ingrese el nombre del permiso',
            'name.unique'=>'El nombre del permiso ya esta en uso',
            'name.min'=>'El nombre del permiso debe tener al menos 5 caracteres',
            'name.max'=>'El nombre del permiso debe tene máximo 20 caracteres',
            'display_name.required'=>'Ingrese el nombre a mostrar',
            'display_name.min'=>'El nombre a mostrar debe tener una longitud de al menos 10 caracteres',
            'display_name.max'=>'El nombre a mostrar debe tener una longitud máxima de 50 caracteres',
            'description.min'=>'La descripción debe tener una longitud de al menos 20 caracteres',
            'description.max'=>'La descripción debe tener una longitud máxima de 250 caracteres',
        ]);
        
        if($validator->fails()){
            return redirect()->route('permissions_create_path')
            ->withInput()
            ->withErrors($validator);
        }
        $permission = new Permission();
        $permission->name =  $request->get('name');
        $permission->display_name =  $request->get('display_name');
        $permission->description =  $request->get('description');
        
        $permission->save();
        
        return redirect()->route('permissions_index_path');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.show', ["permission"=>$permission]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', ["permission"=>$permission]);
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
            'display_name'=>'required|min:10|max:50',
            'description'=>'min:20|max:240',
        ],
        [
            'name.required'=>'Ingrese el nombre del permiso',
            'name.min'=>'El nombre del permiso debe tener al menos 5 caracteres',
            'name.max'=>'El nombre del permiso debe tene máximo 20 caracteres',
            'display_name.required'=>'Ingrese el nombre a mostrar',
            'display_name.min'=>'El nombre a mostrar debe tener una longitud de al menos 10 caracteres',
            'display_name.max'=>'El nombre a mostrar debe tener una longitud máxima de 50 caracteres',
            'description.min'=>'La descripción debe tener una longitud de al menos 20 caracteres',
            'description.max'=>'La descripción debe tener una longitud máxima de 250 caracteres',
        ]);
        
        if($validator->fails()){
            return redirect()->route('permissions_edit_path')
            ->withInput()
            ->withErrors($validator);
        }
        $permission = Permission::findOrFail($id);
        $permission->name =  $request->get('name');
        $permission->display_name =  $request->get('display_name');
        $permission->description =  $request->get('description');
        
        $permission->save();
        
        return redirect()->route('permissions_index_path');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
	$permission->delete();
	return redirect()->route('permissions_index_path');
    }
}
