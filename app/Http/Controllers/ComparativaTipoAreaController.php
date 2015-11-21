<?php

namespace Ghi\Http\Controllers;

use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Areas\TipoAreaRepository;

class ComparativaTipoAreaController extends Controller
{
    /**
     * @var TipoAreaRepository
     */
    private $tipos_area;

    /**
     * ComparativaTipoAreaController constructor.
     *
     * @param TipoAreaRepository $tipos_area
     */
    public function __construct(TipoAreaRepository $tipos_area)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->tipos_area = $tipos_area;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $tipo = $this->tipos_area->getById($id);
        $tipo_cambio = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();

        return view('tipos.comparativa')
            ->withTipo($tipo)
            ->withTipoCambio($tipo_cambio->cambio)
            ->withImporteTotal(0)
            ->withImporteTotalComparativa(0);
    }

    public function comparativa($id)
    {
        $tipo = $this->tipos_area->getById($id);
        $tipo_cambio = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();

        $articulos = $tipo->materialesRequeridos->map(function ($material, $key) use ($tipo_cambio) {
            return [
                'id_material' => $material->id_material,
                'material' => $material->material->descripcion,
                'cantidad_requerida' => $material->cantidad_requerida,
                'precio_estimado' => $material->precio_estimado,
                'precio_estimado_homologado' => $material->getPrecioEstimado($tipo_cambio->cambio),
                'id_moneda' => $material->id_moneda,
                'moneda' => $material->moneda ? $material->moneda->nombre : '',
                'importe_estimado_homologado' => $material->getImporteEstimado($tipo_cambio->cambio),
                'cantidad_comparativa' => $material->cantidad_comparativa,
                'precio_comparativa' => $material->precio_comparativa,
                'precio_comparativa_homologado' => $material->getPrecioComparativa($tipo_cambio->cambio),
                'id_moneda_comparativa' => $material->id_moneda_comparativa,
                'moneda_comparativa' => $material->monedaComparativa ? $material->monedaComparativa->nombre : '',
                'importe_comparativa_homologado' => $material->getImporteComparativa($tipo_cambio->cambio),
                'url' => route('articulos.edit', $material->id_material),
            ];
        });

        return response()->json([
            'tipo_cambio' => $tipo_cambio->cambio,
            'articulos' => $articulos,
        ]);
    }
}
