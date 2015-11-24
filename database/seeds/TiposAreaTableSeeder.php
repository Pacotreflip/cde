<?php

use Ghi\Core\Models\Obra;
use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\AreaTipo;

class TiposAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(Tipo::class, 5)->create(['id_obra' => 1])
//            ->each(function ($nivel1) {
//                factory(Tipo::class, 4)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra])
//                    ->each(function ($nivel2) {
//                        factory(Tipo::class, 10)->create(['parent_id' => $nivel2->id, 'id_obra' => $nivel2->id_obra]);
//                });
//        });

        foreach (Obra::all() as $obra) {
            $junior = factory(AreaTipo::class)->create(['nombre' => 'Junior Suite', 'id_obra' => $obra->id_obra,]);
            factory(AreaTipo::class)->create(['nombre' => 'STD K', 'parent_id' => $junior->id, 'id_obra' => $obra->id_obra,]);
            factory(AreaTipo::class)->create(['nombre' => 'STD D', 'parent_id' => $junior->id, 'id_obra' => $obra->id_obra,]);
            factory(AreaTipo::class)->create(['nombre' => 'PREF K', 'parent_id' => $junior->id, 'id_obra' => $obra->id_obra,]);
            factory(AreaTipo::class)->create(['nombre' => 'PREF D', 'parent_id' => $junior->id, 'id_obra' => $obra->id_obra,]);
        }
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD K']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD D']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF K']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF D']));

        //$mater = factory(Tipo::class)->create(['nombre' => 'Master Suite']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Family Suite']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Villa']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Wedding Suite']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Family Presidential']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Presidential Suite']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

    }
}
