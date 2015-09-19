<?php

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Adquisiciones\Adquisicion;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdquisicionesTest extends TestCase
{
    use WithoutMiddleware;
    
    public function test_crear_una_adquisicion()
    {
        $this->inicioSesion()
            ->seleccionoObra(['nombre' => 'Nueva Obra'])
            ->visit('/adquisiciones')
            ->see('Adquisiciones');
    }
}
