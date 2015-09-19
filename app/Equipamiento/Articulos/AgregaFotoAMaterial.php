<?php

namespace Ghi\Equipamiento\Articulos;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AgregaFotoAMaterial
{
    /**
     * @var Material
     */
    protected $material;

    /**
     * @var UploadedFile
     */
    protected $file;

    protected $thumbnail;

    /**
     * @param Material     $material
     * @param UploadedFile $file
     */
    public function __construct(Material $material, UploadedFile $file, Thumbnail $thumbnail=null)
    {
        $this->material = $material;
        $this->file = $file;
        $this->thumbnail = $thumbnail ?: new Thumbnail;
    }

    /**
     * Agrega una foto a un material
     * 
     * @return Foto
     */
    public function save()
    {
        $foto = $this->nuevaFoto();

        if (! $this->file->move($foto->baseDir(), $foto->nombre)) {
            throw new \Exception('La foto no pudo ser almacenada');
        }

        if (! $this->thumbnail->make($foto->path, $foto->thumbnail_path)) {
            throw new \Exception('El thumbnail no pudo ser creado');
        }

        $this->material->agregaFoto($foto);

        return $foto;
    }

    /**
     * Crea una nueva foto
     * 
     * @return Foto
     */
    protected function nuevaFoto()
    {
        return new Foto(['nombre' => $this->creaNombreArchivo()]);
    }

    /**
     * Crea el nombre final del archivo
     * 
     * @return string
     */
    protected function creaNombreArchivo()
    {
        $name = sha1(time() . $this->file->getClientOriginalName());

        $extesion = $this->file->getClientOriginalExtension();

        return "{$name}.{$extesion}";
    }
}
