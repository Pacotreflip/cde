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

        return $tipos->keyBy('id')->map(function ($area_tipo) {
            return str_repeat('-', $area_tipo->depth + 1).' '.$area_tipo->nombre;
        })->all();
    }

    /**
     * Obtiene una lista de los niveles hoja con su ruta completa.
     * 
     * @return array
     */
    public function getListaUltimosNiveles()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->onlyLeafs()
            ->defaultOrder()
            ->get()
            ->lists('ruta', 'id')
            ->all();
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
