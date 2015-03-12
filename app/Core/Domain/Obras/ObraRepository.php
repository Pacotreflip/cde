<?php  namespace Ghi\Core\Domain\Obras;

interface ObraRepository {

    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

}