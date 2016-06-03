<?php

namespace Ghi\Equipamiento\Recepciones;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AgregaComprobanteARecepcion
{
    /**
     * @var Material
     */
    protected $recepcion;

    /**
     * @var UploadedFile
     */
    protected $file;

    protected $thumbnail;

    /**
     * @param Material     $material
     * @param UploadedFile $file
     */
    public function __construct(Recepcion $recepcion, UploadedFile $file, Thumbnail $thumbnail=null)
    {
        $this->recepcion = $recepcion;
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
        $comprobante = $this->nuevoComprobante();

        $this->file->move($comprobante->baseDir(), $comprobante->nombre);

        $this->thumbnail->make($comprobante->path, $comprobante->thumbnail_path);

        $this->recepcion->agregaComprobante($comprobante);

        return $comprobante;
    }

    /**
     * Crea una nueva foto
     * 
     * @return Foto
     */
    protected function nuevoComprobante()
    {
        return new Comprobante(['nombre' => $this->creaNombreArchivo()]);
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
