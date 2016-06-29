<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Http\Requests\CreateTransferenciaRequest;
use Ghi\Equipamiento\Transferencias\Transferencia;
use Ghi\Equipamiento\Transferencias\Transferencias;
use Illuminate\Support\Facades\DB;


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
            ->withAreas($areas->getListaAreasConArticulos());
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
        
        $transferencia = (new Transferencias($request->all(), $this->getObraEnContexto()))->save();
        
//        DB::connection('cadeco')->beginTransaction();
//
//        try {
//            $origen = Area::findOrFail($request->get('area_origen'));
//
//            $transferencia = Transferencia::crear(
//                $this->getObraEnContexto(),
//                $request->get('fecha_transferencia'),
//                $request->get('observaciones'),
//                auth()->user()->usuario
//            );
//            
//            foreach ($request->get('materiales') as $item) {
//                $material = Material::where('id_material', $item['id'])->firstOrFail();
//                $destino = Area::findOrFail($item['area_destino']);
//
//                $transferencia->transfiereMaterial($material, $origen, $destino, $item['cantidad']);
//            }
//
//            DB::connection('cadeco')->commit();
//        } catch(\Exception $e) {
//            DB::connection('cadeco')->rollback();
//            throw $e;
//        }

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
    
    public function getMateriales(Request $request) {
        $ids = \Ghi\Equipamiento\Inventarios\Inventario::where('cantidad_existencia', '>', 0)->select('id_area')->distinct()->get()->toArray();
        $arrayIds = [];
        foreach($ids as $id){
            array_push($arrayIds, $id['id_area']);
        }
        $materiales = DB::connection('cadeco')->table('equipamiento.inventarios')
                ->join('dbo.materiales', 'equipamiento.inventarios.id_material', '=', 'dbo.materiales.id_material')
                ->whereIn('equipamiento.inventarios.id_area', $arrayIds)
                ->where('equipamiento.inventarios.id_obra', $this->getIdObra())
                ->where('dbo.materiales.descripcion', 'LIKE', '%'.$request->input('q').'%')
                ->where('cantidad_existencia', '>', 0)
                ->select('dbo.materiales.descripcion')
                ->get();  
        $data = [];
        foreach($materiales as $material) {
            array_push($data, $material->descripcion);
        }
        return response()->json($data)
                ->setCallback($request->input('callback'));
    }
    
    public function filtrar(Request $request) {
        $busqueda = $request->input('b');
        $areas = DB::connection('cadeco')->select(
                "SELECT I.id_area
                FROM Equipamiento.inventarios AS I
                INNER JOIN Equipamiento.areas AS A ON I.id_area = A.id
                INNER JOIN dbo.materiales AS M ON I.id_material = M.id_material
                WHERE cantidad_existencia > 0 AND (M.descripcion LIKE '%$busqueda%' 
                OR M.numero_parte LIKE '%$busqueda$') GROUP BY I.id_area");
        
        $data = [];
        foreach($areas as $area) {
            $data[] = [
                'id_area' => $area->id_area,
                'ruta' => \Ghi\Equipamiento\Areas\Area::find($area->id_area)->ruta()
            ];
        }
        return response()->json($data);
    }
}
