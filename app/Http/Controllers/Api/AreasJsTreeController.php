<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Core\Facades\Fractal;
use Ghi\Serializers\SimpleSerializer;
use Ghi\Equipamiento\Areas\AreaRepository;
use Ghi\Equipamiento\Areas\AreaJsTreeTransformer;

class AreasJsTreeController extends ApiController
{
    protected $areas;

    public function __construct(AreaRepository $areas)
    {
        // parent::__construct();

        $this->middleware('auth');
        $this->middleware('context');

        $this->areas = $areas;
        Fractal::setSerializer(new SimpleSerializer);

    }

    public function areas($id = null)
    {
        if ($id) {
            $areas = $this->areas->getHijosDe($id);
        } else {
            $areas = $this->areas->getNivelesRaiz();
        }

        return $this->respondWithCollection($areas, new AreaJsTreeTransformer);
    }
}
