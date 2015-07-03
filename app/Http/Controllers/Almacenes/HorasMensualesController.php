<?php

namespace Ghi\Http\Controllers\Almacenes;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Almacenes\HoraMensual;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
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

        return view('horas-mensuales.create')
            ->withAlmacen($almacen)
            ->withHoras(null);
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

        return redirect()->route('almacenes.show', [$idAlmacen, '#horas-mensuales']);
    }

    /**
     * @param $idAlmacen
     * @param $id
     * @return Response
     */
    public function edit($idAlmacen, $id)
    {
        $almacen = $this->maquinariaRepository->getById($idAlmacen);
        $horas = HoraMensual::findOrFail($id);

        return view('horas-mensuales.edit')
            ->withAlmacen($almacen)
            ->withHoras($horas);
    }

    /**
     * @param $idAlmacen
     * @param $id
     * @param RegistrarHorasMensualesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($idAlmacen, $id, RegistrarHorasMensualesRequest $request)
    {
        $horas = HoraMensual::findOrFail($id);

        $horas->update($request->all());

        flash('Los cambios fueron guardados');

        return redirect()->route('almacenes.show', [$idAlmacen, '#horas-mensuales']);
    }

    /**
     * @param $idAlmacen
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($idAlmacen, $id)
    {
        $hora = HoraMensual::findOrFail($id);
        $hora->delete();

        flash('El registro fue eliminado.');

        return redirect()->route('almacenes.show', [$idAlmacen, '#horas-mensuales']);
    }
}
