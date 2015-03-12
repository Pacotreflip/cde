<?php namespace Ghi\Core\Infraestructure\Conceptos;

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Conceptos\ConceptoRepository;
use Ghi\Core\Domain\Conceptos\NivelParser;

class EloquentConceptoRepository extends BaseRepository implements ConceptoRepository {

    /**
     * @var NivelParser
     */
    private $nivelParser;

    /**
     * @param NivelParser $nivelParser
     */
    function __construct(NivelParser $nivelParser)
    {
        $this->nivelParser = $nivelParser;
    }

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findById($idObra, $idConcepto)
    {
        return Concepto::where('id_obra', '=', $idObra)
            ->where('id_concepto', '=', $idConcepto)
            ->firstOrFail();
    }

    /**
     * @param $idObra
     * @return mixed
     */
    public function findAll($idObra)
    {
        return Concepto::whereIdObra($idObra)
            ->orderBy('nivel')
            ->get();
    }

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findDescendantsOf($idObra, $idConcepto)
    {
        if (is_null($idConcepto))
        {
            return $this->findRootLevels($idObra);
        }
        else
        {
            $concepto = $this->findById($idObra, $idConcepto);

            $numNivel = $this->nivelParser->calculaProfundidad($concepto->nivel) + 1;

            return Concepto::where('id_obra', '=', $idObra)
                ->where('nivel', 'LIKE', $concepto->nivel . '%')
                ->whereRaw("LEN(nivel) / 4 = {$numNivel}")
                ->orderBy('nivel')
                ->get();
        }
    }

    /**
     * @param $idObra
     * @param $idConcepto
     * @return mixed
     */
    public function findAncestorsOf($idObra, $idConcepto)
    {
        $concepto = $this->findById($idObra, $idConcepto);

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
     * @param $idObra
     * @return mixed
     */
    public function findRootLevels($idObra)
    {
        return Concepto::where('id_obra', '=', $idObra)
            ->whereRaw('LEN(nivel) = 4')
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * excluyendo los niveles que son descendientes de conceptos medibles
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
     * Obtiene todos los conceptos que son medibles
     * @param $idObra
     * @return mixed
     */
    public function findOnlyMedibles($idObra)
    {
        return Concepto::whereIdObra($idObra)
            ->whereIn('concepto_medible', [1, 3])
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Realiza una busqueda por descripcion o clave
     * @param $idObra
     * @param $search
     * @param array $filters
     * @return
     */
    public function search($idObra, $search, array $filters)
    {
        return Concepto::whereIdObra($idObra)
            ->where(function($query) use($search)
            {
                $query->where('descripcion', 'LIKE', '%' . $search . '%')
                    ->orWhere('clave_concepto', 'LIKE', '%' . $search . '%');
            })
            ->where(function($query) use($filters)
            {
                foreach ($filters as $filter => $value)
                {
                    $query->where($filter, '=', $value);
                }
            })
            ->get();
    }
}