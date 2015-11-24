<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Foto;
use Ghi\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Http\Requests\AgregaFotoRequest;
use Ghi\Equipamiento\Articulos\AgregaFotoAMaterial;

class FotosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Agrega una foto a un articulo.
     *
     * @param AgregaFotoRequest $request
     * @param $id
     * @return Response
     */
    public function store(AgregaFotoRequest $request, $id)
    {
        $material = Material::findOrFail($id);

        $foto = (new AgregaFotoAMaterial($material, $request->file('foto')))->save();

        if ($request->ajax()) {
            return response()->json($foto->thumbnail_path);
        }
    }

    /**
     * Borra una foto relacionada con un material.
     * 
     * @param  int $id_material
     * @param  int $id
     * @return Response
     */
    public function destroy($id_material, $id)
    {
        $foto = Foto::findOrFail($id);
        $files = [$foto->path, $foto->thumbnail_path];

        $foto->delete();

        File::delete($files);

        return back();
    }
}
