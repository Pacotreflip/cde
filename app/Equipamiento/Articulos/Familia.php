<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Exceptions\FamiliaLlenaException;
use Ghi\Equipamiento\Articulos\Exceptions\MaterialConDiferenteTipoException;

class Familia extends Model
{
    const MAX_HIJOS_EN_FAMILIA = 999;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'materiales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_material';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'descripcion_larga'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Scope para los materiales que son familias
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeFamilias($query, $tipo = null)
    {
        if ($tipo) {
            $query->where('tipo_material', $tipo);
        }

        return $query->whereRaw('LEN(nivel) = 4');
    }

    /**
     * Convierte el valor de tipo de material
     *
     * @return TipoMaerial
     */
    public function getTipoMaterialAttribute($value)
    {
        return new TipoMaterial($value);
    }

    /**
     * Indica si esta familia se encuentra llena
     *
     * @return bool
     */
    protected function estaLlena()
    {
        return $this->children()->count() >= static::MAX_HIJOS_EN_FAMILIA;
    }

    /**
     * Obtiene los hijos de esta familia
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function children()
    {
        return static::whereRaw("LEFT(nivel, 4) = '".$this->nivel."'")
            ->whereRaw('LEN(nivel) > 4')
            ->where('tipo_material', $this->tipo_material)
            ->get();
    }

    /**
     * Agrega un material a esta familia
     *
     * @param Material $material
     * @return Material
     * @throws \Ghi\Equipamiento\Articulos\Exceptions\FamiliaLlenaException
     * @throws \Ghi\Equipamiento\Articulos\Exceptions\MaterialConDiferenteTipoException
     */
    public function agregaMaterial(Material $material)
    {
        if ($this->tipo_material != $material->tipo_material) {
            throw new MaterialConDiferenteTipoException;
        }

        if ($material->isChildrenOf($this)) {
            return $material;
        }

        if ($this->estaLlena()) {
            throw new FamiliaLlenaException;
        }

        $nuevo_nivel = 0;

        if ($this->hasChildren()) {
            $ultimo_hijo  = $this->lastChild();
            $ultimo_nivel = $this->nivelAEntero($ultimo_hijo->nivel);
            $nuevo_nivel  = ++$ultimo_nivel;
        }

        $material->nivel = sprintf("%s%s", $this->nivel, $this->enteroANivel($nuevo_nivel));
        $material->save();

        return $material;
    }

    /**
     * Indica si esta familia tiene hijos.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return static::
            where('tipo_material', $this->tipo_material)
            ->whereRaw("LEFT(nivel, 4) = '".$this->nivel."'")
            ->exists();
    }

    /**
     * Obtiene el primer hijo de esta familia.
     *
     * @return Material|null
     */
    public function firstChild()
    {
        return static::whereRaw("LEFT(nivel, 4) = '".$this->nivel."'")
            ->where('tipo_material', $this->tipo_material)
            ->orderBy('nivel', 'ASC')
            ->first();
    }

    /**
     * Obtiene el ultimo hijo de este material-familia
     *
     * @return Material|null
     */
    public function lastChild()
    {
        return static::whereRaw("LEFT(nivel, 4) = '".$this->nivel."'")
            ->where('tipo_material', $this->tipo_material)
            ->orderBy('nivel', 'DESC')
            ->first();
    }

    /**
     * Convierte un nivel a su representacion de numero entero
     *
     * @param string $nivel
     * @return int
     */
    protected function nivelAEntero($nivel)
    {
        return (int) substr($nivel, 4, 4);
    }

    /**
     * Convierte un numero entero a su representacion de nivel
     *
     * @param int $numero
     * @return string
     */
    protected function enteroANivel($numero)
    {
        return sprintf("%s%s.", $this->cerosParaNivel($numero), $numero);
    }

    /**
     * Genera el numero de ceros que se deben concatenar a un numero
     * entero para generar su representacion de nivel
     *
     * @param int $numero
     * @return string
     */
    protected function cerosParaNivel($numero)
    {
        return str_repeat('0', 3 - strlen($numero));
    }
}