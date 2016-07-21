<?php

namespace Ghi\Equipamiento\Comprobantes;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transferencias\Transferencia;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Cierres\Cierre;
use Ghi\Equipamiento\Transacciones\Entrega;

class Comprobante extends Model {
    protected $connection = 'cadeco';
    protected $table = 'Equipamiento.comprobantes';
    protected $fillable = ['nombre', 'path', 'thumbnail_path'];
    
    public function setNombreAttribute($nombre) {
        $this->attributes['nombre'] = $nombre;
        $this->path = $this->baseDir() . "/" . $nombre;
        $this->thumbnail_path = $this->baseDir() . "/tn-" . $nombre;
    }
    
    public function recepcion() {
        return $this->belongsTo(Recepcion::class, 'id_recepcion');
    }
    public function transferencia() {
        return $this->belongsTo(Transferencia::class, 'id_transferencia');
    }
    public function asignacion() {
        return $this->belongsTo(Asignacion::class, 'id_asignacion');
    }
    public function cierre() {
        return $this->belongsTo(Cierre::class, 'id_cierre');
    }
    public function entrega() {
        return $this->belongsTo(Entrega::class, 'id_entrega');
    }
    public function baseDir() {
        return 'uploads/comprobantes';
    }
}
