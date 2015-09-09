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
    protected $fillable = ['nombre', 'path', 'thumbnail_path'];

    /**
     * @var UploadedFile
     */
    protected $file;

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
     * Crea una nueva fotografia desde un archivo
     * 
     * @param  UploadedFile $file
     * @return self
     */
    public static function desdeArchivo(UploadedFile $file)
    {
        $foto = new static;

        $foto->file = $file;

        $foto->fill([
            'nombre' => $foto->fileName(),
            'path'   => $foto->filePath(),
            'thumbnail_path' => $foto->thumbnailPath(),
        ]);

        return $foto;
    }

    /**
     * Genera el nombre del archivo
     * 
     * @return string
     */
    public function fileName()
    {
        $name = sha1(time() . $this->file->getClientOriginalName());

        $extesion = $this->file->getClientOriginalExtension();

        return "{$name}.{$extesion}";
    }

    /**
     * Genera el directorio del archivo
     * 
     * @return string
     */
    public function filePath()
    {
        return $this->baseDir() . "/" . $this->fileName();
    }

    /**
     * Genera el nombre del archivo thumbnail
     * 
     * @return string
     */
    public function thumbnailPath()
    {
        return $this->baseDir() . '/tn-' . $this->fileName();
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

    /**
     * Mueve el archivo al directorio base con el nombre de la foto
     *
     * @param UploadedFile $file
     * @return self
     */
    public function upload()
    {
        $this->file->move($this->baseDir(), $this->fileName());
        
        $this->creaThumbnail($this->file);

        return $this;
    }

    /**
     * Crea un thumbnail de esta foto
     *
     * @param UploadedFile $file
     * @return self
     */
    public function creaThumbnail()
    {
        Image::make($this->filePath())
            ->resize(200, 200)
            ->save($this->thumbnailPath());

        return $this;
    }
}
