<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Areas\AreaRepository;
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
     * @return \Illuminate\Http\Response
     */
    public function create(AreaRepository $areas)
    {
        return view('transferencias.create')
            ->withAreas($areas->getListaAreas());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTransferenciaRequest $request)
    {
        \DB::connection('cadeco')->beginTransaction();

        try {
            $origen = Area::findOrFail($request->get('area_origen'));

            $transferencia = Transferencia::crear(
                $this->getObraEnContexto(),
                $request->get('fecha'), 
                $origen, 
                $request->get('observaciones'), 
                auth()->user()->usuario
            );
            
            foreach ($request->get('materiales') as $item) {
                $material = Material::where('id_material', $item['id'])->firstOrFail();
                $destino  = Area::findOrFail($item['area_destino']);

                $transferencia->transfiereMaterial($material, $origen, $destino, $item['cantidad']);
            }

            \DB::connection('cadeco')->commit();
        } catch(\Exception $e) {
            \DB::connection('cadeco')->rollback();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
