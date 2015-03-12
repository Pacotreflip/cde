<?php namespace Ghi\Core\Domain\Conceptos;

interface ConceptoRepository {

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findById($idObra, $idConcepto);

    /**
     * @param $idObra
     * @return mixed
     */
    public function findAll($idObra);

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findDescendantsOf($idObra, $idConcepto);

    /**
     * @param $idObra
     * @return mixed
     */
    public function findRootLevels($idObra);

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findAncestorsOf($idObra, $idConcepto);

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * excluyendo los niveles que son descendientes de conceptos medibles
     * @param $idObra
     */
    public function getConceptosList($idObra);

    /**
     * Obtiene todos los conceptos que son medibles
     * @param $idObra
     * @return mixed
     */
    public function findOnlyMedibles($idObra);

    /**
     * Realiza una busqueda por descripcion o clave
     * @param $idObra
     * @param $search
     * @param array $filters
     * @return
     */
    public function search($idObra, $search, array $filters);

}