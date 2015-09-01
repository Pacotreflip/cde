<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    /**
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'clave', 'descripcion'];

    /**
     * Subtipo de area
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subtipo()
    {
        return $this->belongsTo(Subtipo::class, 'subtipo_id');
    }

    /**
     * Inserta un nodo dentro de este nodo
     *
     * @param Area $area
     * @return Area
     */
    public function insertNode(Area $area)
    {
        $ultimo = $this->getUltimoDescendiente();

        if ($ultimo) {
            return $ultimo->addSiblingAfter($area);
        }
        return $this->addSubNode($area);
    }

    /**
     * Crea un subnodo dentro de este nodo
     *
     * @param Area $area
     * @return Area
     */
    public function addSubNode(Area $area)
    {
        $area->lft = $this->lft + 1;
        $area->rgt = $this->lft + 2;
        $this->actualizaNodosMayoresQue($this->lft);
        $area->save();

        return $area;
    }

    /**
     * Inserta un nodo antes de este nodo
     *
     * @param Area $area
     * @return Area
     */
    public function addSiblingBefore(Area $area)
    {
        $area->lft = $this->lft;
        $area->rgt = $this->rgt;
        $this->actualizaNodosMayoresQue($this->lft - 1);
        $area->save();
        return $area;
    }

    /**
     * Inserta un nodo despues de este nodo
     *
     * @param Area $area
     * @return Area
     */
    public function addSiblingAfter(Area $area)
    {
        $area->lft = $this->rgt + 1;
        $area->rgt = $this->rgt + 2;
        $this->actualizaNodosMayoresQue($this->rgt);
        $area->save();
        return $area;
    }

    /**
     * Actualiza los nodos que son mayores a otro en su izquierda y derecha
     *
     * @param $number
     */
    protected function actualizaNodosMayoresQue($number)
    {
        static::where('lft', '>', $number)->update(['lft' => DB::raw('lft + 2')]);
        static::where('rgt', '>', $number)->update(['rgt' => DB::raw('rgt + 2')]);
    }

    /**
     * Obtiene el ultimo nodo descendiente inmediato de este nodo
     *
     * @return mixed
     */
    public function getUltimoDescendiente()
    {
        return static::where('id', '<>', $this->id)
            ->whereBetween('lft', [$this->lft, $this->rgt])
            ->orderBy('lft', 'DESC')
            ->first();
    }

    public static function getNivelesRaiz()
    {

    }
}
