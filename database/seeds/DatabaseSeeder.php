<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    private $tables = [
//        'maquinaria.periodos_conciliacion',
//        'maquinaria.reportes_operacion',
//        'maquinaria.horas',
//        'dbo.inventarios',
//        'dbo.entregas',
//        'dbo.items',
//        'dbo.transacciones',
//        'dbo.sucursales',
//        'dbo.empresas',
//        'dbo.almacenes',
//        'dbo.materiales',
//        'dbo.conceptos',
//        'dbo.unidades',
//        'dbo.usuarios',
//        'dbo.obras',
    ];

    private $truncate = [
    ];

    private $tablesWithAutoId = [
//        'maquinaria.periodos_conciliacion',
//        'maquinaria.reportes_operacion',
//        'maquinaria.horas',
//        'dbo.inventarios',
//        'dbo.items',
//        'dbo.transacciones',
//        'dbo.sucursales',
//        'dbo.empresas',
//        'dbo.almacenes',
//        'dbo.materiales',
//        'dbo.conceptos',
//        'dbo.obras',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->cleanDatabaseCadeco();

//        $this->call('ObrasTableSeeder');
//        $this->call('UsuariosCadecoTableSeeder');
//        $this->call('EmpresasTableSeeder');
//        $this->call('UnidadesTableSeeder');
//        $this->call('MaterialesTableSeeder');
//        $this->call('AlmacenesTableSeeder');
//        $this->call('ConceptosTableSeeder');
//        $this->call('RentasTableSeeder');
//        $this->call('ReportesOperacionTableSeeder');
//		$this->call('ConciliacionesTableSeeder');

        Model::reguard();
    }

    /**
     * Limpia los datos de las tablas del sao
     */
    protected function cleanDatabaseCadeco()
    {
        foreach ($this->truncate as $table) {
            DB::statement("ALTER TABLE {$table} NOCHECK CONSTRAINT all;");

            DB::table($table)->delete();

            if (in_array($table, $this->tablesWithAutoId)) {
                DB::statement("DBCC CHECKIDENT('{$table}', RESEED, 0);");
            }

            DB::statement("ALTER TABLE {$table} WITH CHECK CHECK CONSTRAINT all;");
        }
    }
}
