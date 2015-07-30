<?php

namespace Ghi\Domain\Core;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $casts = [
        'numero' => 'int',
        'cantidad' => 'float',
        'importe' => 'float',
        'precio_unitario' => 'float',
        'anticipo' => 'float'
    ];
    /**
     * Transaccion relacionada con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Item antecedente de este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemAntecedente()
    {
        return $this->belongsTo(Item::class, 'item_antecedente', 'id_item');
    }
}
