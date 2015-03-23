<?php namespace Ghi\Almacenes\Http\Controllers;

use Ghi\Almacenes\Domain\AlmacenMaquinariaRepository;
use Ghi\Almacenes\Http\Requests\RegistrarHorasMensualesRequest;
use Ghi\Core\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class HorasMensualesController extends Controller {

    /**
     * @var AlmacenMaquinariaRepository
     */
    private $maquinariaRepository;

    /**
     * @param AlmacenMaquinariaRepository $maquinariaRepository
     */
    function __construct(AlmacenMaquinariaRepository $maquinariaRepository)
    {
        $this->maquinariaRepository = $maquinariaRepository;
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
        $data['creado_por'] = Auth::user()->usuario;

        $horaMensual = $this->maquinariaRepository->registraHorasMensuales($idAlmacen, $data);

        Flash::success('Un nuevo registro de horas mensuales fue creado.');

        return redirect()->route('almacenes.show', [$idAlmacen]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
