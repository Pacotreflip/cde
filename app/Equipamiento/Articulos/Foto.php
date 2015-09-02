<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Foto extends Model
{
    /**
     * Conexion default de base de datos
     *
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Campos que se pueden asignar masivamente
     *
     * @var array
     */
    protected $fillable = ['nombre', 'path'];

    /**
     * Directorio base de almacenamiento de las fotografias
     *
     * @var string
     */
    protected $directorioBase = '/articulos/fotos';

    /**
     * Articulo relacionado con esta fotografia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    /**
     * Crea una nueva imagen con un nombre
     *
     * @param $nombre
     * @return mixed
     */
    public function conNombre($nombre)
    {
        return (new static)->guardarComo($nombre);
    }

    /**
     * Asigna el nombre y directorio para la foto
     *
     * @param $nombre
     * @return $this
     */
    protected function guardarComo($nombre)
    {
        $this->nombre = sprintf("%s-%s", time(), $nombre);
        $this->path   = sprintf("%s/%s", $this->directorioBase, $nombre);

        return $this;
    }

    /**
     * Mueve el archivo al directorio base con el nombre de la foto
     *
     * @param UploadedFile $file
     */
    public function mover(UploadedFile $file)
    {
        $file->move($this->directorioBase, $this->nombre);
    }
}
