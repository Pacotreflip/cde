<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Domain\Core\Conceptos\ConceptoTransformer;
use Ghi\Domain\Core\Conceptos\ConceptoRepository;
use Ghi\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ConceptosController extends ApiController
{
    /**
     * @var ConceptoRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $availableParams = [
        'medible' => 'concepto_medible',
        'clave' => 'clave_concepto',
    ];

    /**
     * @var string
     */
    private $searchParam = 'search';

    /**
     * @param ConceptoRepository $repository
     */
    public function __construct(ConceptoRepository $repository)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->repository = $repository;
    }

    /**
     * Muestra una lista de los conceptos del presupuesto de obra
     *
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        if ($request->has($this->searchParam)) {
            $conceptos = $this->repository->search($request->get($this->searchParam), $this->parseParams($request->all()));

            return $this->respondWithCollection($conceptos, new ConceptoTransformer);
        }

        if ($request->has('medible')) {
            $conceptos = $this->repository->getMedibles();

            return $this->respondWithCollection($conceptos, new ConceptoTransformer);
        }

        return $this->errorWrongArgs('Especifique parametros de busqueda.');
    }

    /**
     * Muestra un concepto del presupuesto de obra
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $concepto = $this->repository->getById($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound('Equipo no encontrado');
        } catch (\Exception $e) {
            return $this->errorInternalError();
        }

        return $this->respondWithItem($concepto, new ConceptoTransformer);
    }

    /**
     * @param array $input
     * @return array
     */
    public function parseParams(array $input)
    {
        $params = array_intersect_key($this->availableParams, $input);

        $filters = [];

        foreach ($params as $key => $param) {
            $filters[$param] = $input[$key];
        }

        return $filters;
    }
}
