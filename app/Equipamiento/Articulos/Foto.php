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
    protected $fillable = ['nombre', 'path', 'thumbnail_path'];

    /**
     * @param string $nombre
     */
    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = $nombre;
        $this->path = $this->baseDir() . "/" . $nombre;
        $this->thumbnail_path = $this->baseDir() . "/tn-" . $nombre;
    }

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
     * Directorio base de almacenamiento de esta fotografia
     * 
     * @return string
     */
    public function baseDir()
    {
        return 'articulo/fotos';
    }
}
