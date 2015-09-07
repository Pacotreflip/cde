<?php

namespace Ghi\Equipamiento\Articulos;

class Factory
{
    /**
     * Crea un nuevo material
     *
     * @param string $nombre
     * @param string $descripcion
     * @param string $numero_parte
     * @param Unidad $unidad
     * @param Unidad $unidad_compra
     * @param TipoMaterial $tipo
     * @return Material
     */
    public function make($nombre, $descripcion, $numero_parte, Unidad $unidad, Unidad $unidad_compra, TipoMaterial $tipo)
    {
        $material = $this->materialBase($nombre, $descripcion, $numero_parte, $unidad, $unidad_compra, $tipo);

        if ((string) $tipo == TipoMaterial::TIPO_MATERIALES) {
            return $this->nuevoDeTipoMaterial($material);
        }

        if ((string) $tipo == TipoMaterial::TIPO_MANO_OBRA) {
            return $this->nuevoDeTipoManoObra($material);
        }

        if ((string) $tipo == TipoMaterial::TIPO_SERVICIOS) {
            return $this->nuevoDeTipoServicio($material);
        }

        if ((string) $tipo == TipoMaterial::TIPO_MAQUINARIA) {
            return $this->nuevoDeTipoMaquinaria($material);
        }

        if ((string) $tipo == TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO) {
            return $this->nuevoDeTipoHerramienta($material);
        }
    }

    /**
     * Crea un material base
     *
     * @param string $nombre
     * @param string $descripcion
     * @param string $numero_parte
     * @param Unidad $unidad
     * @param Unidad $unidad_compra
     * @param TipoMaterial $tipo
     */
    protected function materialBase($nombre, $descripcion, $numero_parte, Unidad $unidad, Unidad $unidad_compra, TipoMaterial $tipo)
    {
        $material = new Material([
                'descripcion'       => $nombre,
                'descripcion_larga' => $descripcion,
                'numero_parte'      => $numero_parte,
                'codigo_externo'    => $numero_parte,
        ]);
        $material->unidad        = $unidad->unidad;
        $material->unidad_compra = $unidad_compra->unidad;
        $material->equivalencia  = 1;
        $material->marca         = 1;
        $material->tipo_material = $tipo;

        return $material;
    }

    protected function nuevoDeTipoMaterial(Material $material)
    {
        return $material;
    }

    /**
     * Crea un material de tipo mano de obra
     *
     * @param Material $material
     * @return Material
     */
    protected function nuevoDeTipoManoObra(Material $material)
    {
        $material->marca = 0;

        return $material;
    }

    /**
     * Crea un material de tipo servicio
     *
     * @param Material $material
     * @return Material
     */
    protected function nuevoDeTipoServicio(Material $material)
    {
        return $material;
    }

    /**
     * Crea un material de tipo servicio
     *
     * @param Material $material
     * @return Material
     */
    protected function nuevoDeTipoMaquinaria(Material $material)
    {
        $material->equivalencia     = 0;
        $material->unidad_compra    = null;
        $material->unidad_capacidad = $material->unidad;

        return $material;
    }

    /**
     * Crea un material de tipo herramienta
     *
     * @param Material $material
     * @return Material
     */
    protected function nuevoDeTipoHerramienta(Material $material)
    {
        $material->marca = 0;

        return $material;
    }
}