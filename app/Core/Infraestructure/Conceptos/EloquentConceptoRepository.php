<?php namespace Ghi\Core\Infraestructure\Conceptos;

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Conceptos\ConceptoRepository;
use Ghi\Core\Domain\Conceptos\NivelParser;
use Ghi\Core\Services\Context;
use Illuminate\Config\Repository;

class EloquentConceptoRepository extends BaseRepository implements ConceptoRepository {

    /**
     * @var NivelParser
     */
    private $nivelParser;

    /**
     * @var array
     */
    private $filterFields = ['concepto_medible', 'clave'];

    /**
     * @param Context $context
     * @param Repository $config
     * @param NivelParser $nivelParser
     */
    function __construct(Context $context, Repository $config, NivelParser $nivelParser)
    {
        parent::__construct($context, $config);

        $this->nivelParser = $nivelParser;
    }

    /**
     * Obtiene un concepto por su id
     *
     * @param $idConcepto
     * @return mixed
     */
    public function getById($idConcepto)
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->where('id_concepto', '=', $idConcepto)
            ->firstOrFail();
    }

    /**
     * Obtiene todos los conceptos de una obra
     *
     * @return mixed
     */
    public function getAll()
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene los descendientes de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function getDescendantsOf($idObra, $idConcepto)
    {
        if (is_null($idConcepto))
        {
            return $this->getRootLevels($idObra);
        }

        $concepto = $this->getById($idObra, $idConcepto);

        $numNivel = $this->nivelParser->calculaProfundidad($concepto->nivel) + 1;

        return Concepto::where('id_obra', '=', $idObra)
            ->where('nivel', 'LIKE', $concepto->nivel . '%')
            ->whereRaw("LEN(nivel) / 4 = {$numNivel}")
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function getAncestorsOf($idObra, $idConcepto)
    {
        $concepto = $this->getById($idObra, $idConcepto);

        $niveles = $this->nivelParser->extraeNiveles($concepto->nivel);

        $query = Concepto::where('id_obra', '=', $idObra);

        $query->where(function($query) use($niveles)
        {
            foreach ($niveles as $nivel)
            {
                $query->orWhere('nivel', '=', $nivel);
            }
        });

        return $query->OrderBy('nivel')->get();
    }

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @param $idObra
     * @return mixed
     */
    public function getRootLevels($idObra)
    {
        return Concepto::where('id_obra', '=', $idObra)
            ->whereRaw('LEN(nivel) = 4')
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * hasta llegar a los niveles de conceptos medibles
     *
     * @param $idObra
     */
    public function getConceptosList($idObra)
    {
        return Concepto::selectRaw("id_obra, id_material, nivel, id_concepto, REPLICATE(' | ', LEN(nivel)/4) + '->' + descripcion as descripcion")
            ->whereIdObra($idObra)
            ->whereNull('id_material')
            ->whereExists(function($query)
            {
                $query->select(\DB::raw(1))
                    ->from('conceptos as medibles')
                    ->whereRaw('conceptos.id_obra = medibles.id_obra')
                    ->whereRaw('LEFT(medibles.nivel, LEN(conceptos.nivel)) = conceptos.nivel')
                    ->where('concepto_medible', '>', '0');
            })
            ->orderBy('nivel')
            ->remember(120)
            ->lists('descripcion', 'id_concepto');
    }

    /**
     * Obtiene todos los conceptos que son medibles/facturables
     *
     * @return mixed
     */
    public function getMedibles()
    {
        return Concepto::whereIdObra($this->context->getId())
            ->whereIn('concepto_medible', [1, 3])
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Realiza una busqueda por descripcion o clave
     *
     * @param $search
     * @param array $filters
     * @return
     */
    public function search($search, array $filters)
    {
        $filters = $this->parseFilters($filters);

        return Concepto::where('id_obra', $this->context->getId())
            ->where(function($query) use($search)
            {
                $query->where('descripcion', 'LIKE', '%' . $search . '%')
                    ->orWhere('clave_concepto', 'LIKE', '%' . $search . '%');
            })
            ->where(function($query) use($filters)
            {
                foreach ($filters as $filter)
                {
                    $query->{$filter['method']}($filter['field'], $filter['value']);
                }
            })
            ->get();
    }

    /**
     * @param array $filters
     * @return array
     */
    private function parseFilters(array $filters)
    {
        $filterFields = [];

        foreach ($this->filterFields as $field)
        {
            if ( ! array_key_exists($field, $filters)) {
                continue;
            }

            if ($field == 'concepto_medible') {
                $filterFields[] = [
                    'field' => $field,
                    'method' => 'whereIn',
                    'value' => [Concepto::CONCEPTO_MEDIBLE, Concepto::CONCEPTO_FACTURABLE],
                ];

                continue;
            }

            $filterFields[] = [
                'field' => $field,
                'method' => 'where',
                'value' => $filters[$field],
            ];
        }

        return $filterFields;
    }

}
