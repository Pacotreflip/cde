<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;
use Ghi\Core\Models\Obra;
use Illuminate\Support\Facades\DB;
class Concepto extends Model
{
    protected $connection = 'cadeco';
    protected $fillable = ['id_obra', 'descripcion', 'control_equipamiento'];
    
    /**
     * @var bool
     */
    protected $primaryKey = 'id_concepto';
    protected $table = 'conceptos';
    
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'id_concepto' => 'integer',
        'activo' => 'boolean',
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaNivel();
        });
    }
    
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
    
    
    protected function asignaNivel()
    {
        $nivel_ingresado = $this->nivel;
        
        if($nivel_ingresado == null){
            
            
            $resultado = DB::connection("cadeco")->select("
                SELECT 
                case when max(nivel) is null then '000.' else cast(replicate('0',3 -len(left(max(nivel),3)+1)) as varchar)+cast((left(max(nivel),3)+1) as varchar) +'.' end nivel
  FROM [conceptos] where id_obra = {$this->id_obra}
  and len(nivel) = 4;");
            $this->nivel = $resultado[0]->nivel;
        }else{
            
        }
    }
    
    /**
     * Indica si este concepto tiene descendientes
     *
     * @return bool
     */
    public function tieneDescendientes()
    {
        return static::where('id_obra', $this->id_obra)
            ->where('nivel', '<>', $this->nivel)
            ->whereRaw("LEFT(nivel, LEN('{$this->nivel}')) = '{$this->nivel}'")
            ->exists();
    }

    /**
     * Obtiene los descendientes directos de este concepto
     *
     * @return Concepto|\Illuminate\Database\Eloquent\Collection
     */
    public function getDescendientes()
    {
        $numero_nivel = $this->getNumeroNivel() + 1;

        return static::where('id_obra', $this->id_obra)
            ->where('nivel', 'LIKE', "{$this->nivel}%")
            ->whereRaw("LEN (nivel)/4 = {$numero_nivel}")
            ->get();
    }

    /**
     * Obtiene el ancestro inmediato de este concepto
     *
     * @return Concepto|\Illuminate\Database\Eloquent\Collection
     */
    public function getAncestro()
    {
        return static::where('id_obra', $this->id_obra)
            ->where('nivel', $this->getNivelAncestro())
            ->first();
    }
    
    public function getRutaAttribute(){
        $nivel = $this->nivel;
        $no_niveles = strlen($nivel)/4;
        $niveles_ancestros = array();
        $largo=4;
        for($c = 0; $c<$no_niveles; $c++){
            $nivel_nuevo = substr($nivel, 0, $largo);
            $ancestro = Concepto::where('nivel', $nivel_nuevo)->where("id_obra", $this->id_obra)->first();
            $niveles_ancestros[] = $ancestro->descripcion;
            $largo+=4;
        }
        $ruta = implode(" / ", $niveles_ancestros);
        return $ruta;
    }
}
