<?php

namespace Ghi\Equipamiento\Areas;

use League\Fractal\TransformerAbstract;

class AreaJsTreeTransformer extends TransformerAbstract
{
    public function transform(Area $area)
    {
        return [
            'id'       => $area->id,
            'text'     => $area->nombre,
            'children' => (bool) $area->getDescendantCount(),
        ];
    }
}
