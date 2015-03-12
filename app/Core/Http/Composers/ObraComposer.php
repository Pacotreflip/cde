<?php  namespace Ghi\Core\Http\Composers;

use Ghi\Core\Domain\Obras\ObraRepository;
use Ghi\Core\Services\Context;
use Illuminate\Contracts\View\View;

class ObraComposer {

    /**
     * @var Context
     */
    private $context;

    /**
     * @var ObraRepository
     */
    private $repository;

    /**
     * @param Context $context
     * @param ObraRepository $repository
     */
    function __construct(Context $context, ObraRepository $repository)
    {
        $this->context = $context;
        $this->repository = $repository;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('currentObra', $this->getCurrentObra());
    }

    /**
     * @return mixed
     */
    private function getCurrentObra()
    {
        if ($this->context->isEstablished())
        {
            return $this->repository->getById($this->context->getId());
        }

        return null;
    }
}