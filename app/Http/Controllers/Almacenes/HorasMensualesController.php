<?php

namespace Ghi\Http\Controllers\Almacenes;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Http\Requests\Almacenes\RegistrarHorasMensualesRequest;
use Ghi\Http\Controllers\Controller;

class HorasMensualesController extends Controller
{
    /**
     * @var AlmacenMaquinariaRepository
     */
    private $maquinariaRepository;

    /**
     * @param AlmacenMaquinariaRepository $maquinariaRepository
     */
    public function __construct(AlmacenMaquinariaRepository $maquinariaRepository)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->maquinariaRepository = $maquinariaRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $idAlmacen
     * @return Response
     */
    public function create($idAlmacen)
    {
        $almacen = $this->maquinariaRepository->getById($idAlmacen);

        return view('horas-mensuales.create')->withAlmacen($almacen);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $idAlmacen
     * @param RegistrarHorasMensualesRequest $request
     * @return Response
     */
    public function store($idAlmacen, RegistrarHorasMensualesRequest $request)
    {
        $data = $request->all();
        $data['creado_por'] = auth()->user()->usuario;

        $horaMensual = $this->maquinariaRepository->registraHorasMensuales($idAlmacen, $data);

        flash()->success('Un nuevo registro de horas mensuales fue creado.');

        return redirect()->route('almacenes.show', [$idAlmacen]);
    }
}
