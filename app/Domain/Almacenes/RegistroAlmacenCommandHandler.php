<?php

namespace Ghi\Domain\Almacenes;

class RegistroAlmacenCommandHandler implements CommandHandler
{
    use DispatchableTrait;

    protected $almacenRepository;

    /**
     * @var TenantContextInterface
     */
    private $context;

    /**
     * @param AlmacenRepository $almacenRepository
     * @param TenantContext $context
     */
    public function __construct(AlmacenRepository $almacenRepository, TenantContext $context)
    {
        $this->almacenRepository = $almacenRepository;
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
        $material = Material::findOrFail($command->material);
        $categoria = Clasificacion::findOrFail($command->categoria);
        $propiedad = Propiedad::findOrFail($command->propiedad);

        $almacen = Almacen::registro(
            $command->descripcion,
            $command->economico,
            $command->material
        );

        $almacen->material()->associate($material);
        $almacen->categoria()->associate($categoria);
        $almacen->propiedad()->associate($propiedad);

        $this->almacenRepository->save($almacen, $this->context->getTenantId());

        $this->dispatchEventsFor($almacen);

        return $almacen;
    }
}
