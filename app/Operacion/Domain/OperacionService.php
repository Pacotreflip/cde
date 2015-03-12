<?php namespace Ghi\Operacion\Domain;

use Carbon\Carbon;
use Ghi\Core\App\Contexts\TenantContext;
use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Core\Domain\Conceptos\Contracts\ConceptoRepository;
use Ghi\Core\Domain\Obras\Contracts\ObraRepository;
use Ghi\Core\Domain\Usuarios\Contracts\UserSaoRepository;
use Ghi\Maquinaria\Domain\Operacion\Contracts\OperacionRepository;
use Ghi\Maquinaria\Domain\Operacion\Exceptions\ReporteDeOperacionYaExisteException;
use Ghi\Maquinaria\Domain\Operacion\Exceptions\ReporteOperacionCerradoException;
use Ghi\Operacion\Domain\Exceptions\ReporteDeOperacionYaExisteException;
use Ghi\SharedKernel\Contracts\EquipoRepository;
use Laracasts\Commander\Events\DispatchableTrait;

class OperacionService {

    use DispatchableTrait;

    /**
     * @var OperacionRepository
     */
    private $operacionRepository;

    /**
     * @var TenantContext
     */
    private $context;

    /**
     * @var ObraRepository
     */
    private $obraRepository;

    /**
     * @var UserSaoRepository
     */
    private $userSaoRepository;

    /**
     * @var ConceptoRepository
     */
    private $conceptoRepository;

    /**
     * @var EquipoRepository
     */
    private $equipoRepository;

    /**
     * @param TenantContext $context
     * @param OperacionRepository $operacionRepository
     * @param ObraRepository $obraRepository
     * @param UserSaoRepository $userSaoRepository
     * @param ConceptoRepository $conceptoRepository
     * @param EquipoRepository $equipoRepository
     */
    function __construct(
        TenantContext $context,
        OperacionRepository $operacionRepository,
        ObraRepository $obraRepository,
        UserSaoRepository $userSaoRepository,
        ConceptoRepository $conceptoRepository,
        EquipoRepository $equipoRepository
    )
    {
        $this->operacionRepository = $operacionRepository;
        $this->context = $context;
        $this->obraRepository = $obraRepository;
        $this->userSaoRepository = $userSaoRepository;
        $this->conceptoRepository = $conceptoRepository;
        $this->equipoRepository = $equipoRepository;
    }

    /**
     * @return mixed
     */
    public function getEquiposPaginated()
    {
        return $this->equipoRepository->getAllPaginated();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getEquipoById($id)
    {
        return $this->equipoRepository->findById($id);
    }

    /**
     * Inicia el reporte de operacion de un almacen
     * @param $idEquipo
     * @param $fecha
     * @param $horometroInicial
     * @param $kilometrajeInicial
     * @param $observaciones
     * @param $usuario
     * @return static
     */
    public function iniciaOperacion($idEquipo, $fecha, $horometroInicial, $kilometrajeInicial, $observaciones, $usuario)
    {
        $this->existeReporteEnFecha($idEquipo, Carbon::createFromFormat('d-m-Y', $fecha));

        $equipo = $this->equipoRepository->findById($idEquipo);
        $obra = $this->obraRepository->findById($this->context->getTenantId());
        $userSao = $this->userSaoRepository->findByUsername($usuario);

        if (empty($horometroInicial))
        {
            $horometroInicial = null;
        }

        if (empty($kilometrajeInicial))
        {
            $kilometrajeInicial = null;
        }

        $operacion = ReporteActividad::iniciaOperacion(
            $obra,
            $equipo,
            $fecha,
            $horometroInicial,
            $kilometrajeInicial,
            $observaciones,
            $userSao
        );

        $this->operacionRepository->save($operacion);

        $this->dispatchEventsFor($operacion);

        return $operacion;
    }

    /**
     * Busca un reporte de operacion por fecha
     * @param $idAlmacen
     * @param $fecha
     * @return mixed
     */
    public function findByFecha($idAlmacen, $fecha)
    {
        return $this->operacionRepository->findByFecha($idAlmacen, $fecha);
    }

    /**
     * @param $idAlmacen
     * @param $fecha
     * @param null $horometroFinal
     * @param null $kilometrajeFinal
     * @return mixed
     */
    public function cierraOperacion($idAlmacen, $fecha, $horometroFinal = null, $kilometrajeFinal = null)
    {
        $reporte = $this->operacionRepository->findByFecha($idAlmacen, $fecha);

        $reporte->cierraOperacion($horometroFinal, $kilometrajeFinal);

        return $this->operacionRepository->save($reporte);
    }

    /**
     * Borra un reporte de operacion
     *
     * @param $idEquipo
     * @param $fecha
     * @throws ReporteOperacionCerradoException
     */
    public function borraReporte($idEquipo, $fecha)
    {
        $reporte = $this->operacionRepository->findByFecha($idEquipo, $fecha);

        if ($reporte->cerrado)
        {
            throw new ReporteOperacionCerradoException;
        }

        $this->operacionRepository->delete($reporte);
    }

    /**
     * @param $idEquipo
     * @return mixed
     */
    public function getAllForEquipoPaginated($idEquipo)
    {
        $equipo = $this->equipoRepository->findById($idEquipo);

        return $this->operacionRepository->getAllForEquipoPaginated($equipo);
    }

    /**
     * @return mixed
     */
    public function getTiposHoraList()
    {
        return $this->operacionRepository->getTiposHoraList();
    }

    /**
     * @param $idEquipo
     * @param $fecha
     * @param $idHora
     */
    public function borraHora($idEquipo, $fecha, $idHora)
    {
        $reporte = $this->findByFecha($idEquipo, $fecha);

        $this->operacionRepository->deleteHora($reporte, $idHora);
    }

    /**
     * @param $idEquipo
     * @param $fecha
     * @throws ReporteDeOperacionYaExisteException
     */
    protected function existeReporteEnFecha($idEquipo, $fecha)
    {
        if ($this->operacionRepository->existsByDate($idEquipo, $fecha))
        {
            throw new ReporteDeOperacionYaExisteException;
        }
    }

}