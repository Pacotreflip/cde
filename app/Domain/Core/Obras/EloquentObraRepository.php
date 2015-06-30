<?php

namespace Ghi\Domain\Core\Obras;

use Ghi\Domain\Core\BaseRepository;

class EloquentObraRepository extends BaseRepository implements ObraRepository
{
    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return Obra
     */
    public function getById($id)
    {
        return Obra::where('id_obra', $id)->firstOrFail();
    }
}
