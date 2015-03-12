<?php namespace Ghi\Core\Domain\Obras;

use Ghi\Core\App\Contexts\TenantContext;
use Ghi\Core\Domain\Obras\Contracts\ObraRepository;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

class RegistrarObraCommandHandler implements CommandHandler {

    use DispatchableTrait;

    protected $context;

    protected $obraRepository;

    /**
     * @param ObraRepository $obraRepository
     * @param TenantContext $context
     */
    function __construct(ObraRepository $obraRepository, TenantContext $context)
    {
        $this->context = $context;
        $this->obraRepository = $obraRepository;
    }


    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $actualConnectionName = $this->context->getConnectionName();

        $this->context->setConnectionName($command->connection);

        $obra = Obra::registrar(
            $command->nombre,
            $command->descripcion,
            $command->estadoObra,
            $command->constructora,
            $command->cliente,
            $command->facturar,
            $command->responsable,
            $command->rfc,
            $command->direccion,
            $command->ciudad,
            $command->codigoPostal,
            $command->estado,
            $command->moneda,
            $command->iva,
            $command->fechaInicial,
            $command->fechaFinal
        );

        $this->obraRepository->save($obra);

        $this->dispatchEventsFor($obra);

        $this->context->setConnectionName($actualConnectionName);

        return $obra;
    }

}