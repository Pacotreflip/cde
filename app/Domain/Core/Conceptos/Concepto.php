<?php

namespace Ghi\Domain\Core\Conceptos;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Concepto extends Model
{
    const CONCEPTO_FACTURABLE = 1;
    const CONCEPTO_MEDIBLE = 3;

    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'conceptos';

    /**
     * @var string
     */
    protected $primaryKey = 'id_concepto';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    protected $presenter = ConceptoPresenter::class;

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'id_concepto' => 'integer'
    ];

    /**
     * Indica si el concepto es medible
     *
     * @return bool
     */
    public function esMedible()
    {
        if ($this->concepto_medible == 3 || $this->concepto_medible == 1) {
            return true;
        }
        return false;
    }

    /**
     * Indica si este concepto es un material
     *
     * @return bool
     */
    public function esMaterial()
    {
        if ($this->id_material) {
            return true;
        }
        return false;
    }

    /**
     * Indica si este concepto tiene descendientes
     *
     * @return bool
     */
    public function tieneDescendientes()
    {
        return static::where('id_obra', $this->id_obra)
            ->where('nivel', '<>', $this->nivel)
            ->whereRaw("LEFT(nivel, LEN('{$this->nivel}')) = '{$this->nivel}'")
            ->exists();
    }
}
