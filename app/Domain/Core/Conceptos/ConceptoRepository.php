<?php

namespace Ghi\Domain\Core\Conceptos;

use Illuminate\Database\Eloquent\Collection;

interface ConceptoRepository
{
    /**
     * Obtiene un concepto por su id
     *
     * @param $idConcepto
     * @return Concepto
     */
    public function getById($idConcepto);

    /**
     * Obtiene todos los conceptos de una obra
     *
     * @return Collection|Concepto
     */
    public function getAll();

    /**
     * Obtiene los descendientes de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return Collection|Concepto
     */
    public function getDescendantsOf($idObra, $idConcepto);

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @param $idObra
     * @return Collection|Concepto
     */
    public function getRootLevels($idObra);

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return Collection|Concepto
     */
    public function getAncestorsOf($idObra, $idConcepto);

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * hasta llegar a los niveles de conceptos medibles
     *
     * @param $idObra
     * @return array
     */
    public function getConceptosList($idObra);

    /**
     * Obtiene todos los conceptos que son medibles/facturables
     *
     * @return Collection|Concepto
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
