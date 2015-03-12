<?php namespace Ghi\Conciliacion\Domain\Commands;

use Ghi\Maquinaria\Domain\Conciliacion\Contracts\PeriodoRepository;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

class CerrarPeriodoCommandHandler implements CommandHandler {

    use DispatchableTrait;

    /**
     * @var PeriodoRepository
     */
    private $periodoRepository;

    /**
     * @param PeriodoRepository $periodoRepository
     */
    function __construct(PeriodoRepository $periodoRepository)
    {
        $this->periodoRepository = $periodoRepository;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $periodo = $this->periodoRepository->findById($command->id);

        $periodo->cerrar($command->horasEfectivas, $command->horasReparacionMayor, $command->horasOcio);

        $this->dispatchEventsFor($periodo);

        return $this->periodoRepository->save($periodo);
    }

}