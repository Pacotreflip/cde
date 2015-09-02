<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    /**
     * Conexion por default de base de datos
     *
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Campos que se pueden asignar masivamente
     *
     * @var array
     */
    protected $fillable = ['nombre', 'numero_parte', 'descripcion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }

    /**
     * Agrega una foto a este articulo
     *
     * @param Foto $foto
     * @return Model
     */
    public function agregaFoto(Foto $foto)
    {
        return $this->fotos()->save($foto);
    }
}
