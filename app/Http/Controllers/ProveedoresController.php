<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Proveedores\Tipo;
use Ghi\Equipamiento\Proveedores\Proveedor;

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
            $busqueda = $request->get('buscar');

            $proveedores = Proveedor::soloProveedores()
                ->where(function ($query) use ($busqueda) {
                    $query->where('razon_social', 'like', '%' . $busqueda . '%')
                        ->orWhere('rfc', 'like', '%' . $busqueda . '%');
                })
                ->orderBy('razon_social')
                ->paginate(30);
        } else {
            $proveedores = Proveedor::soloProveedores()
                ->orderBy('razon_social')->paginate(30);
        }

        return view('proveedores.index')
            ->withProveedores($proveedores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = [
            Tipo::PROVEEDOR => (new Tipo(Tipo::PROVEEDOR))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES => (new Tipo(Tipo::PROVEEDOR_MATERIALES))->getDescripcion(),
            Tipo::CONTRATISTA => (new Tipo(Tipo::CONTRATISTA))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES_CONTRATISTA => (new Tipo(Tipo::PROVEEDOR_MATERIALES_CONTRATISTA))->getDescripcion(),
        ];

        return view('proveedores.create')
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProveedorRequest  $request
     * @return Response
     */
    public function store(Requests\CreateProveedorRequest $request)
    {
        $proveedor = new Proveedor($request->all());
        $proveedor->tipo_empresa = new Tipo($request->get('tipo_empresa'));
        $proveedor->save();

        Flash::success('El proveedor fue agregado.');

        return redirect()->route('proveedores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
        $tipos = [
            Tipo::PROVEEDOR => (new Tipo(Tipo::PROVEEDOR))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES => (new Tipo(Tipo::PROVEEDOR_MATERIALES))->getDescripcion(),
            Tipo::CONTRATISTA => (new Tipo(Tipo::CONTRATISTA))->getDescripcion(),
            Tipo::PROVEEDOR_MATERIALES_CONTRATISTA => (new Tipo(Tipo::PROVEEDOR_MATERIALES_CONTRATISTA))->getDescripcion(),
        ];

        return view('proveedores.edit')
            ->withProveedor($proveedor)
            ->withTipos($tipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProveedorRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Requests\UpdateProveedorRequest $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $proveedor->fill($request->all());
        $proveedor->tipo_empresa = new Tipo($request->get('tipo_empresa'));
        $proveedor->save();

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
