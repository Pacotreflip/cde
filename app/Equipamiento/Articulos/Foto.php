<?php

namespace Ghi\Equipamiento\Articulos;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Foto extends Model
{
    /**
     * Conexion default de base de datos
     *
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * Nombre de la tabla
     *
     * @var string
     */
    protected $table = 'Equipamiento.material_fotos';

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
    protected $directorioBase = 'articulo/fotos';

    /**
     * Articulo relacionado con esta fotografia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material');
    }

    /**
     * Crea una nueva imagen con un nombre
     *
     * @param string $nombre
     * @return $this
     */
    public static function conNombre($nombre)
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
        $this->path   = sprintf("%s/%s", $this->directorioBase, $this->nombre);
        $this->thumbnail_path = sprintf("%s/tn-%s", $this->directorioBase, $this->nombre);

        return $this;
    }

    /**
     * Mueve el archivo al directorio base con el nombre de la foto
     *
     * @param UploadedFile $file
     * @return $this
     */
    public function mover(UploadedFile $file)
    {
        $file->move($this->directorioBase, $this->nombre);
        
        $this->creaThumbnail($file);

        return $this;
    }

    /**
     * Crea un thumbnail de esta foto
     *
     * @param UploadedFile $file
     * @return $this
     */
    public function creaThumbnail(UploadedFile $file)
    {
        Image::make($this->path)
            ->resize(200, 200)
            ->save($this->thumbnail_path);

        return $this;
    }
}
