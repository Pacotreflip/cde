<?php

namespace Ghi\Equipamiento\Asignaciones;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ghi\Equipamiento\Comprobantes\Comprobante;
use Ghi\Equipamiento\Comprobantes\Thumbnail;

class AgregaComprobanteAAsignacion
{
    /**
     * @var Material
     */
    protected $asignacion;

    /**
     * @var UploadedFile
     */
    protected $file;

    protected $thumbnail;

    /**
     * @param Material     $material
     * @param UploadedFile $file
     */
    public function __construct(Asignacion $asignacion, UploadedFile $file, Thumbnail $thumbnail=null)
    {
        $this->asignacion = $asignacion;
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

        if(in_array($this->file->getClientOriginalExtension(), ['jpeg','bmp','png','jpg'])) {
            $this->thumbnail->make($comprobante->path, $comprobante->thumbnail_path);
        } else {
            $comprobante->thumbnail_path = $comprobante->baseDir().'/pdf.png';
        }
        $this->asignacion->agregaComprobante($comprobante);

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
