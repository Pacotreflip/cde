<?php

namespace Ghi\Equipamiento\Articulos;

use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\ReporteCostos\AreaDreams;
use Ghi\Equipamiento\ReporteCostos\MaterialSecrets;
class Materiales
{
    /**
     * Busca un material por su id.
     *
     * @param $id
     * @return Material
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Material::findOrFail($id);
    }

    /**
     * Obtiene todos los materiales.
     *
     * @param array $except Ids de los materiales a excluir
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll($except = [])
    {
        return Material::soloMateriales()
            ->whereNotIn('id_material', $except)
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Obtiene todos los materiales paginados.
     *
     * @param int $howMany
     * @param array $except Ids de los materiales a excluir
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPaginated($howMany = 30, $except = [])
    {
        return Material::soloMateriales()
            ->whereNotIn('id_material', $except)
            ->orderBy('descripcion')
            ->paginate($howMany);
    }

    /**
     * Obtiene los materiales en forma de lista.
     * 
     * @return array
     */
    public function getAsList()
    {
        return Material::soloMateriales()
            ->orderBy('descripcion')
            ->lists('descripcion', 'id_material')
            ->all();
    }

    /**
     * Busqueda de materiales.
     *
     * @param string $busqueda Texto a buscar
     * @param int    $howMany  Numero de articulos por pagina
     * @param array  $except   Ids de los materiales a excluir
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscar($busqueda, $howMany = 30, $except = [])
    {
        return Material::materialesEquipamiento()
            ->whereNotIn('materiales.id_material', $except)
            ->where(function ($query) use($busqueda) {
                $query->where('materiales.descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('materiales.numero_parte', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('materiales.unidad', 'LIKE', '%'.$busqueda.'%');
            })
            ->select(DB::raw("materiales.id_material, materiales.tipo_material, materiales.descripcion, materiales.numero_parte, materiales.unidad, materiales.id_clasificador"))
            ->groupBy(DB::raw("materiales.id_material, materiales.tipo_material, materiales.descripcion, materiales.numero_parte, materiales.unidad, materiales.id_clasificador"))
            ->orderBy('descripcion')
            ->paginate($howMany);
    }

    /**
     * Obtiene una lista de unidades como arreglo.
     *
     * @return array
     */
    public function getListaUnidades()
    {
        return Unidad::selectRaw('unidad AS id, unidad')
            ->orderBy('unidad')
            ->lists('unidad', 'unidad')
            ->all();
    }
    /**
     * Obtiene una lista de áreas de reporte.
     *
     * @return array
     */
    public function getListaAreasReporte()
    {
       
        $lista["A99"] = "- SELECCIONE ÁREA -";
        $areas =  AreaDreams::selectRaw('id, area_dreams')
            ->orderBy('area_dreams')
            ->get();
        foreach ($areas as $area) {
            $lista[$area->id] = $area->area_dreams;
        }
        return $lista;
    }
    /**
     * Obtiene una lista de materiales del hotel secrets.
     *
     * @return array
     */
    public function getListaMaterialesSecrets()
    {
        $lista["A99"] = "NO RELACIONAR";
        $materiales =  MaterialSecrets::selectRaw('id, descripcion')
            ->orderBy('descripcion')
            ->get();
        foreach ($materiales as $material) {
            $lista[$material->id] = $material->descripcion;
        }
        return $lista;
    }
    
    /**
     * Obtiene una lista de unidades como arreglo.
     *
     * @return array
     */
    public function getListaMonedas()
    {
        return Moneda::selectRaw('id_moneda AS id, nombre as moneda')
            ->orderBy('moneda')
            ->lists('moneda', 'id')
            ->all();
    }

    /**
     * Obtiene una lista de clasificadores como arreglo.
     *
     * @return array
     */
    public function getListaClasificadores()
    {
        return Clasificador::orderBy('nombre')
            ->lists('nombre', 'id')
            ->all();
    }

    /**
     * Obtiene una lista de las familias de materiales.
     *
     * @param null $tipo
     * @return array
     */
    public function getListaFamilias($tipo = null)
    {
        return Familia::familias($tipo)
            ->orderBy('descripcion')
            ->lists('descripcion', 'id_material');
    }

    /**
     * Obtiene una lista de los tipos de material como arreglo.
     *
     * @return array
     */
    public function getListaTipoMateriales()
    {
        return [
            TipoMaterial::TIPO_MATERIALES => (new TipoMaterial(TipoMaterial::TIPO_MATERIALES))->getDescripcion(),
            TipoMaterial::TIPO_MANO_OBRA  => (new TipoMaterial(TipoMaterial::TIPO_MANO_OBRA))->getDescripcion(),
            TipoMaterial::TIPO_SERVICIOS  => (new TipoMaterial(TipoMaterial::TIPO_SERVICIOS))->getDescripcion(),
            TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO => (new TipoMaterial(TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO))->getDescripcion(),
            TipoMaterial::TIPO_MAQUINARIA => (new TipoMaterial(TipoMaterial::TIPO_MAQUINARIA))->getDescripcion(),
        ];
    }

    /**
     * Guarda los cambios de un material.
     *
     * @param Material $material
     * @return bool
     */
    public function save(Material $material)
    {
        return $material->save();
    }
    
    public function getOrdenCompra($id_obra, $id_material){
        $ordenes = Transaccion::ordenesCompraMateriales()
            ->where('id_obra', $id_obra)
            ->where('id_material', $id_material)
            ->join("items", "items.id_transaccion","=","transacciones.id_transaccion")
            ->orderBy('numero_folio', 'ASC')->get();
        return $ordenes;
    }
}
