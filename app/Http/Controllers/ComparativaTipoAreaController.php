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
}
