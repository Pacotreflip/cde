<?php

namespace Ghi\Domain\Core\Conceptos;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\Core\Context;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Collection;

class EloquentConceptoRepository extends BaseRepository implements ConceptoRepository
{
    /**
     * @var NivelParser
     */
    private $nivelParser;

    /**
     * @var array
     */
    private $filterFields = ['concepto_medible', 'clave'];

    /**
     * {@inheritdoc}
     */
    public function __construct(Context $context, Repository $config, NivelParser $nivelParser)
    {
        parent::__construct($context, $config);

        $this->nivelParser = $nivelParser;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->where('id_concepto', $id)
            ->firstOrFail();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->orderBy('nivel')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescendantsOf($id)
    {
        $idObra = $this->context->getId();

        if (is_null($id)) {
            return $this->getRootLevels();
        }

        $concepto = $this->getById($id);

        $numNivel = $this->nivelParser->calculaProfundidad($concepto->nivel) + 1;

        return Concepto::where('id_obra', '=', $idObra)
            ->where('nivel', 'LIKE', $concepto->nivel . '%')
            ->whereRaw("LEN(nivel) / 4 = {$numNivel}")
            ->orderBy('nivel')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestorsOf($id)
    {
        $idObra = $this->context->getId();
        $concepto = $this->getById($id);

        $niveles = $this->nivelParser->extraeNiveles($concepto->nivel);

        $query = Concepto::where('id_obra', $idObra);

        $query->where(function ($query) use ($niveles) {
            foreach ($niveles as $nivel) {
                $query->orWhere('nivel', $nivel);
            }
        });

        return $query->OrderBy('nivel')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getRootLevels()
    {
        $idObra = $this->context->getId();

        return Concepto::where('id_obra', $idObra)
            ->whereRaw('LEN(nivel) = 4')
            ->orderBy('nivel')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getConceptosList()
    {
        return Concepto::selectRaw("id_obra, id_material, nivel, id_concepto, REPLICATE(' | ', LEN(nivel)/4) + '->' + descripcion as descripcion")
            ->whereIdObra($this->context->getId())
            ->whereNull('id_material')
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('conceptos as medibles')
                    ->whereRaw('conceptos.id_obra = medibles.id_obra')
                    ->whereRaw('LEFT(medibles.nivel, LEN(conceptos.nivel)) = conceptos.nivel')
                    ->where('concepto_medible', '>', '0');
            })
            ->orderBy('nivel')
            ->remember(120)
            ->lists('descripcion', 'id_concepto')
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getMedibles()
    {
        return Concepto::whereIdObra($this->context->getId())
            ->whereIn('concepto_medible', [Concepto::CONCEPTO_MEDIBLE, Concepto::CONCEPTO_FACTURABLE])
            ->orderBy('nivel')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function search($search, array $filters)
    {
        $filters = $this->parseFilters($filters);

        return Concepto::where('id_obra', $this->context->getId())
            ->where(function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%')
                    ->orWhere('clave_concepto', 'LIKE', '%' . $search . '%');
            })
            ->where(function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->{$filter['method']}($filter['field'], $filter['value']);
                }
            })
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    private function parseFilters(array $filters)
    {
        $filterFields = [];

        foreach ($this->filterFields as $field) {
            if (! array_key_exists($field, $filters)) {
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
