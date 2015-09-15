<?php

namespace Ghi\Http\Controllers;

use Ghi\Core\Contracts\Context;

class ContextController extends Controller
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
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

        return redirect()->route('areas.index');
    }
}
