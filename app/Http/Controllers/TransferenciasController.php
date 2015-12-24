<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Http\Requests\CreateTransferenciaRequest;
use Ghi\Equipamiento\Transferencias\Transferencia;

class TransferenciasController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transferencias = Transferencia::all();

        return view('transferencias.index')
            ->withTransferencias($transferencias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Areas $areas
     * @return \Illuminate\Http\Response
     */
    public function create(Areas $areas)
    {
        return view('transferencias.create')
            ->withAreas($areas->getListaAreas());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTransferenciaRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreateTransferenciaRequest $request)
    {
        DB::connection('cadeco')->beginTransaction();

        try {
            $origen = Area::findOrFail($request->get('area_origen'));

            $transferencia = Transferencia::crear(
                $this->getObraEnContexto(),
                $request->get('fecha_transferencia'),
                $request->get('observaciones'),
                auth()->user()->usuario
            );
            
            foreach ($request->get('materiales') as $item) {
                $material = Material::where('id_material', $item['id'])->firstOrFail();
                $destino = Area::findOrFail($item['area_destino']);

                $transferencia->transfiereMaterial($material, $origen, $destino, $item['cantidad']);
            }

            DB::connection('cadeco')->commit();
        } catch(\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }

        return response()->json(['path' => route('transferencias.show', [$transferencia])]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transferencia = Transferencia::with('items')->findOrFail($id);

        return view('transferencias.show')
            ->withTransferencia($transferencia);
    }
}
