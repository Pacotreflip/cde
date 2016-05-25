<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Proveedores\Tipo;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Http\Requests\CreateProveedorRequest;
use Illuminate\Support\Facades\DB;
use \Ghi\Equipamiento\Proveedores\Sucursal;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('buscar')) {
            $proveedores = $this->buscar($request->get('buscar'));
        } else {
            $proveedores = Proveedor::soloProveedores()
                ->orderBy('razon_social')->paginate(30);
        }

        return view('proveedores.index')
            ->withProveedores($proveedores);
    }

    /**
     * Hace una busqueda de proveedores
     * 
     * @param  string $busqueda
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function buscar($busqueda)
    {
        return Proveedor::soloProveedores()->join('sucursales','sucursales.id_empresa', '=', 'empresas.id_empresa')
                ->where(function ($query) use ($busqueda) {
                    $query
                        
                        ->where('empresas.razon_social', 'like', '%' . $busqueda . '%')
                        ->orWhere('empresas.rfc', 'like', '%' . $busqueda . '%')
                        ->orWhere('empresas.nombre_corto', 'like', '%' . $busqueda . '%')
                        ->orWhere('empresas.nombre_contacto', 'like', '%' . $busqueda . '%')
                        ->orWhere('sucursales.contacto', 'like', '%' . $busqueda . '%')
                        ->orWhere('sucursales.email', 'like', '%' . $busqueda . '%')
                        ->orWhere('sucursales.telefono', 'like', '%' . $busqueda . '%')
                        ->orWhere('correo', 'like', '%' . $busqueda . '%');
                })
                ->orderBy('razon_social')
                ->paginate(30);
    }

    /**
     * Lista de los tipos de proveedor disponibles
     * 
     * @return array
     */
    protected function listaTipos()
    {
        return [
            Tipo::PROVEEDOR => (new Tipo(Tipo::PROVEEDOR))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES => (new Tipo(Tipo::PROVEEDOR_MATERIALES))->getDescripcion(),
            Tipo::CONTRATISTA => (new Tipo(Tipo::CONTRATISTA))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES_CONTRATISTA => (new Tipo(Tipo::PROVEEDOR_MATERIALES_CONTRATISTA))->getDescripcion(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = $this->listaTipos();

        return view('proveedores.create')
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateProveedorRequest $request
     * @return Response
     */
    public function store(CreateProveedorRequest $request)
    {
        DB::connection('cadeco')->beginTransaction();
        $proveedor = new Proveedor($request->all());
        $proveedor->tipo_empresa = new Tipo($request->get('tipo_empresa'));
        $proveedor->save();
        $sucursal = new Sucursal();
        $sucursal->descripcion = "MATRIZ";
        $sucursal->contacto = $request->nombre_contacto;
        $sucursal->email = $request->correo;
        $sucursal->telefono = $request->telefono;
        $sucursal->direccion = $request->direccion;
        $sucursal->id_empresa = $proveedor->id_empresa;
        $sucursal->save();

        Flash::success('El proveedor fue agregado.');
        DB::connection('cadeco')->commit();
        return redirect()->route('proveedores.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $tipos = $this->listaTipos();

        return view('proveedores.edit')
            ->withProveedor($proveedor)
            ->withTipos($tipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\UpdateProveedorRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(Requests\UpdateProveedorRequest $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->fill($request->all());
        $proveedor->tipo_empresa = new Tipo($request->get('tipo_empresa'));
        $proveedor->save();
        
        $sucursal = $proveedor->sucursales->first();
        $sucursal->descripcion = "MATRIZ";
        $sucursal->contacto = $request->nombre_contacto;
        $sucursal->email = $request->correo;
        $sucursal->telefono = $request->telefono;
        $sucursal->direccion = $request->direccion;
        $sucursal->save();

        Flash::success('Los cambios fueron guardados');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
