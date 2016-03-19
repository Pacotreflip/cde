<?php

namespace Ghi\Equipamiento\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'sucursales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_sucursal';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'direccion', 'ciudad', 'estado'
        , 'codigo', 'telefono', 'fax'
        , 'contacto'
        , 'casa_central'
        , 'email'
        , 'cargo'
        , 'telefono_movil'
        , 'observaciones'
        , 'UsuarioRegistro'
        , 'UsuarioValido'
        ];

    /**
     * @var bool
     */
    public $timestamps = false;
    
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, "id_empresa");
    }
    
}
