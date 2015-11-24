<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Contracts\Context;

class AreasTipo
{
    /**
     * Contexto de la aplicacion.
     *
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Obtiene un tipo de area por su id.
     *
     * @param int $id
     * @return AreaTipo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return AreaTipo::with('materialesRequeridos')->findOrFail($id);
    }

    /**
     * Obtiene la estructura completa de tipos.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->defaultOrder()
            ->withDepth()
            ->get();
    }

    /**
     * Obtiene los tipos que son raiz.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->whereIsRoot()
            ->defaultOrder()
            ->get();
    }

    /**
     * Obtiene una lista de tipos como un arreglo.
     *
     * @return array
     */
    public function getListaTipos()
    {
        $tipos = $this->getAll();

        $lista = [];
        foreach ($tipos as $tipo) {
            $lista[$tipo->id] = str_repeat('-', $tipo->depth + 1).''.$tipo->nombre;
        }

        return $lista;
    }

    /**
     * Obtiene una lista de los niveles hoja con su ruta completa.
     * 
     * @return array
     */
    public function getListaUltimosNiveles()
    {
        $leafs = AreaTipo::where('id_obra', $this->context->getId())
            ->onlyLeafs()
            ->defaultOrder()
            ->withDepth()
            ->get();

        $lista = [];

        foreach ($leafs as $tipo_area) {
            $lista[$tipo_area->id] = $this->getRutaNodo($tipo_area);
        }

        return $lista;
    }

    /**
     * Genera la ruta de un nodo.
     * 
     * @param  AreaTipo   $nodo
     * @param  string $separador
     * @return string
     */
    protected function getRutaNodo($nodo, $separador = '/')
    {
        $ruta = '';

        foreach ($nodo->getAncestors() as $seccion) {
            $ruta .= $seccion->nombre.$separador;
        }

        $ruta .= $nodo->nombre;

        return $ruta;
    }

    /**
     * Elimina un area tipo.
     *
     * @param AreaTipo $tipo
     * @return bool
     */
    public function delete(AreaTipo $tipo)
    {
        return $tipo->delete();
    }

    /**
     * Persiste los cambios de un area tipo.
     *
     * @param AreaTipo $tipo
     * @return bool|mixed
     */
    public function save(AreaTipo $tipo)
    {
        return $tipo->save();
    }
}
