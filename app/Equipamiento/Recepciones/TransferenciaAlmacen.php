<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Equipamiento\Areas\Almacen;

class TransferenciaAlmacen extends Model
{
    use TransaccionTrait;
    private $tipo_transaccion = 34;
    private $opciones = 65537;
    
    protected $fillable = [
        'tipo_transaccion',
        'fecha',
        'id_almacen',
        'referencia',
        'observaciones',
        'opciones',
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //$model->asignaFolioTransaccion();
            $model->asignaFolioAlternativo();
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
     * Empresa relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Items relacionados con esta transaccion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(TransferenciaAlmacenItem::class, 'id_transaccion', 'id_transaccion');
    }

    
    
}
