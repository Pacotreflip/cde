<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Domain\Core\App\SimpleSerializer;
use Ghi\Domain\Core\Conceptos\ConceptoJsTreeTransformer;
use Ghi\Domain\Core\Facades\Fractal;
use Ghi\Http\Controllers\ApiController;
use Ghi\Domain\Core\Conceptos\ConceptoRepository;

class ConceptosJsTreeController extends ApiController
{

    /**
     * @var ConceptoRepository
     */
    private $repository;

    function __construct(ConceptoRepository $repository)
    {
        $this->repository = $repository;
        Fractal::setSerializer(new SimpleSerializer);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function getRoot()
    {
        $roots = $this->repository->getRootLevels();
        return $this->respondWithCollection($roots, new ConceptoJSTreeTransformer);
    }

    public function getNode($id)
    {
        $node = $this->repository->getDescendantsOf($id);
        return $this->respondWithCollection($node, new ConceptoJSTreeTransformer);
    }
}
