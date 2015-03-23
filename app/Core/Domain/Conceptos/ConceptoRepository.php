<?php namespace Ghi\Core\Domain\Conceptos;

interface ConceptoRepository {

    /**
     * Obtiene un concepto por su id
     *
     * @param $idConcepto
     * @return mixed
     */
    public function getById($idConcepto);

    /**
     * Obtiene todos los conceptos de una obra
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Obtiene los descendientes de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function getDescendantsOf($idObra, $idConcepto);

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @param $idObra
     * @return mixed
     */
    public function getRootLevels($idObra);

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function getAncestorsOf($idObra, $idConcepto);

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * hasta llegar a los niveles de conceptos medibles
     *
     * @param $idObra
     */
    public function getConceptosList($idObra);

    /**
     * Obtiene todos los conceptos que son medibles/facturables
     *
     * @return mixed
     */
    public function getMedibles();

    /**
     * Realiza una busqueda de conceptos por descripcion o clave
     *
     * @param $search
     * @param array $filters
     * @return
     */
    public function search($search, array $filters);

}
