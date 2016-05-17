<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Equipamiento\Areas\Almacen;

class EntradaAlmacen extends Model
{
    use TransaccionTrait;
    private $tipo_transaccion = 33;
    private $opciones = 1;
    
    protected $fillable = [
        'id_antecedente',
        'fecha',
        'id_empresa',
        'id_sucursal',
        'id_moneda',
        'observaciones',
        'referencia',
        'tipo_transaccion',
        'opciones'
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaFolioTransaccion();
            $model->asignaComentario();
        });
    }
    
    public function getTipoTransaccion(){
        return $this->tipo_transaccion;
    }
    
    public function getOpciones(){
        return $this->opciones;
    }
    
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var array
     */
    protected $dates = ['fecha', 'cumplimiento', 'vencimiento'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Obra relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Items relacionados con esta transaccion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(EntradaAlmacenItem::class, 'id_transaccion', 'id_transaccion');
    }

    
}
