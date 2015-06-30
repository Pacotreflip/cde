<?php namespace Ghi\Conciliacion\Domain\Commands;

use Ghi\Core\Domain\Obras\ObraRepository;
use Ghi\Core\Domain\Usuarios\UserSaoRepository;
use Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository;
use Ghi\Conciliacion\Domain\Periodos\ConciliacionRepository;
use Ghi\Conciliacion\Domain\ProveedorRepository;
use Ghi\Conciliacion\Domain\Periodos\Conciliacion;
use Ghi\Operacion\Domain\ReporteActividadRepository;
use Ghi\SharedKernel\Contracts\EquipoRepository;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

class GenerarPeriodoCommandHandler implements CommandHandler {

    use DispatchableTrait;

    /**
     * @var ReporteActividadRepository
     */
    private $operacionRepository;

    /**
     * @var ConciliacionRepository
     */
    private $periodoRepository;

    /**
     * @var ObraRepository
     */
    private $obraRepository;

    /**
     * @var ProveedorRepository
     */
    private $proveedorRepository;

    /**
     * @var EquipoRepository
     */
    private $equipoRepository;

    /**
     * @var UserSaoRepository
     */
    private $userSaoRepository;

    /**
     * @var EloquentOrdenRentaRepository
     */
    private $contratoRentaRepository;

    /**
     * @param ReporteActividadRepository $operacionRepository
     * @param ConciliacionRepository $periodoRepository
     * @param ObraRepository $obraRepository
     * @param ProveedorRepository $proveedorRepository
     * @param EquipoRepository $equipoRepository
     * @param UserSaoRepository $userSaoRepository
     * @param ContratoRentaRepository $contratoRentaRepository
     */
    function __construct(
        ReporteActividadRepository $operacionRepository,
        ConciliacionRepository $periodoRepository,
        ObraRepository $obraRepository,
        ProveedorRepository $proveedorRepository,
        EquipoRepository $equipoRepository,
        UserSaoRepository $userSaoRepository,
        ContratoRentaRepository $contratoRentaRepository
    )
    {
        $this->operacionRepository = $operacionRepository;
        $this->periodoRepository = $periodoRepository;
        $this->obraRepository = $obraRepository;
        $this->proveedorRepository = $proveedorRepository;
        $this->equipoRepository = $equipoRepository;
        $this->userSaoRepository = $userSaoRepository;
        $this->contratoRentaRepository = $contratoRentaRepository;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $this->operacionRepository->existenHorasPorConciliarEnPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);

        $this->periodoRepository->sePuedeConciliar($command->idObra, $command->idProveedor, $command->idEquipo, $command->fechaInicial, $command->fechaFinal);

        $obra = $this->obraRepository->findById($command->idObra);
        $proveedor = $this->proveedorRepository->findById($command->idProveedor);
        $equipo = $this->equipoRepository->findById($command->idEquipo);
        $usuarioSao = $this->userSaoRepository->findByUsername($command->usuario);

        $horasContrato = $this->contratoRentaRepository->getHorasContratoVigenteDeEquipoPorPeriodo(
            $command->idObra, $command->idProveedor, $command->idEquipo, $command->fechaInicial, $command->fechaFinal
        );

        $efectivas = $this->operacionRepository->sumaHorasEfectivasPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $repMayor = $this->operacionRepository->sumaHorasReparacionMayorPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $repMenor = $this->operacionRepository->sumaHorasReparacionMenorPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $mantenimiento = $this->operacionRepository->sumaHorasMantenimientoPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $ocio = $this->operacionRepository->sumaHorasOcioPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $horometroInicial = $this->operacionRepository->getHorometroIncialPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $horometroFinal = $this->operacionRepository->getHorometroFinalPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $horasHorometro = $this->operacionRepository->getHorasHorometroPorPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);
        $diasConOperacion = $this->operacionRepository->diasConOperacionEnPeriodo($command->idEquipo, $command->fechaInicial, $command->fechaFinal);

        $periodo = Conciliacion::generar(
            $obra, $proveedor, $equipo, $command->fechaInicial, $command->fechaFinal, $diasConOperacion,
            $horasContrato, $efectivas, $repMayor, $repMenor, $mantenimiento,
            $ocio, $horometroInicial, $horometroFinal, $horasHorometro,
            $command->observaciones, $usuarioSao
        );

        return $this->periodoRepository->save($periodo);
    }

}