<?php

namespace Ghi\Equipamiento\Recepciones;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Comprobante extends Model {
    protected $connection = 'cadeco';
    protected $table = 'Equipamiento.recepcion_fotos';
    protected $fillable = ['nombre', 'path', 'thumbnail_path'];
    
    public function setNombreAttribute($nombre) {
        $this->attributes['nombre'] = $nombre;
        $this->path = $this->baseDir() . "/" . $nombre;
        $this->thumbnail_path = $this->baseDir() . "/tn-" . $nombre;
    }
    
    public function recepcion() {
        return $this->belongsTo(Recepcion::class, 'id_recepcion');
    }
    
    public function baseDir() {
        return 'recepcion/comprobantes';
    }
}
