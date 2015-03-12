<?php namespace Ghi\Core\Http\Controllers;

use Ghi\Core\Domain\Obras\ObraRepository;
use Ghi\Core\Services\Context;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class ContextController extends Controller {

    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    function __construct(Context $context)
    {
        $this->middleware('auth');

        $this->context = $context;
    }

    /**
     * Establece el contexto de la aplicacion (base de datos y id de obra)
     *
     * @param $databaseName
     * @param $id
     * @return Response
     */
    public function set($databaseName, $id)
    {
        $this->context->setId($id);

        $this->context->setDatabaseName($databaseName);

        return redirect()->route('pages.home');
    }

}
