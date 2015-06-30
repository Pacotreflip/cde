<?php

namespace Ghi\Domain\ReportesActividad\Commands;

use Ghi\Domain\Core\Conceptos\ConceptoRepository;
use Ghi\Operacion\Domain\Contracts\OperacionRepository;
use Ghi\Operacion\Domain\Hora;
use Laracasts\Commander\CommandHandler;

class ReportarHorasCommandHandler implements CommandHandler
{
    /**
     * @var ConceptoRepository
     */
    private $conceptoRepository;
    /**
     * @var OperacionRepository
     */
    private $operacionRepository;
    /**
     * @var TenantContext
     */
    private $context;

    public function __construct(ConceptoRepository $conceptoRepository, OperacionRepository $operacionRepository, TenantContext $context)
    {
        $this->conceptoRepository = $conceptoRepository;
        $this->operacionRepository = $operacionRepository;
        $this->context = $context;
    }


    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $concepto = null;

        if (! empty($command->idConcepto)) {
            $concepto = $this->conceptoRepository->findById($this->context->getTenantId(), $command->idConcepto);
        }

        $reporte = $this->operacionRepository->findByFecha($command->idEquipo, $command->fecha);

        $hora = Hora::creaHora(
            $command->idTipoHora,
            $command->cantidad,
            $concepto,
            $command->conCargo,
            $command->observaciones,
            $command->usuario
        );

        return $hora->reportarEn($reporte);
    }
}
