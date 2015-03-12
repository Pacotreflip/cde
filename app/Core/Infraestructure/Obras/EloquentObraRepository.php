<?php  namespace Ghi\Core\Infraestructure\Obras;

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Core\Domain\Obras\ObraRepository;

class EloquentObraRepository extends BaseRepository implements ObraRepository {

    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Obra::where('id_obra', $id)->firstOrFail();
    }

}