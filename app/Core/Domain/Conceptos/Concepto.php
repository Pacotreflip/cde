<?php namespace Ghi\Core\Domain\Conceptos;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Concepto extends Model {

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

    /**
     * Indica si el concepto es medible
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
     * @return bool
     */
    public function esMaterial()
    {
        if ($this->id_material) {
            return true;
        }

        return false;
    }

}
