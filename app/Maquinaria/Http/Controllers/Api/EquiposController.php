<?php  namespace Ghi\Maquinaria\Http\Controllers\Api;

use Ghi\Maquinaria\Api\EquipoTransformer;
use Ghi\Core\App\ApiController;
use Ghi\Core\App\Facades\Fractal;
use Ghi\SharedKernel\Contracts\EquipoRepository;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class EquiposController extends ApiController {

    /**
     * @var EquipoRepository
     */
    private $equipoRepository;

    function __construct(EquipoRepository $equipoRepository)
    {
        $this->equipoRepository = $equipoRepository;
    }

    /**
     * Muestra una lista de los equipos (almacenes de maquinaria)
     * de una obra
     * @return mixed
     */
    public function lists()
    {
        $equipos = $this->equipoRepository->getAll();

        $resource = new Collection($equipos, new EquipoTransformer);

        return Fractal::createData($resource)->toJson();
    }

    /**
     * Muestra un equipo
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $equipo = $this->equipoRepository->findById($id);

        $resource = new Item($equipo, new EquipoTransformer);

        return Fractal::createData($resource)->toJson();
    }
}