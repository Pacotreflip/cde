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
     * @param $id_almacen
     * @return Response
     */
    public function create($id_almacen)
    {
        $almacen = $this->maquinariaRepository->getById($id_almacen);

        return view('horas-mensuales.create')
            ->withAlmacen($almacen)
            ->withHoras(null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id_almacen
     * @param RegistrarHorasMensualesRequest $request
     * @return Response
     */
    public function store($id_almacen, RegistrarHorasMensualesRequest $request)
    {
        $data = $request->all();
        $data['creado_por'] = auth()->user()->usuario;

        $horaMensual = $this->maquinariaRepository->registraHorasMensuales($id_almacen, $data);

        flash()->success('Un nuevo registro de horas mensuales fue creado.');

        return redirect()->route('almacenes.show', [$id_almacen, '#horas-mensuales']);
    }

    /**
     * @param $id_almacen
     * @param $id
     * @return Response
     */
    public function edit($id_almacen, $id)
    {
        $almacen = $this->maquinariaRepository->getById($id_almacen);
        $horas = HoraMensual::findOrFail($id);

        return view('horas-mensuales.edit')
            ->withAlmacen($almacen)
            ->withHoras($horas);
    }

    /**
     * @param $id_almacen
     * @param $id
     * @param RegistrarHorasMensualesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id_almacen, $id, RegistrarHorasMensualesRequest $request)
    {
        $horas = HoraMensual::findOrFail($id);

        $horas->update($request->all());

        flash('Los cambios fueron guardados');

        return redirect()->route('almacenes.show', [$id_almacen, '#horas-mensuales']);
    }

    /**
     * @param $id_almacen
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id_almacen, $id)
    {
        $hora = HoraMensual::findOrFail($id);
        $hora->delete();

        flash('El registro fue eliminado.');

        return redirect()->route('almacenes.show', [$id_almacen, '#horas-mensuales']);
    }
}
