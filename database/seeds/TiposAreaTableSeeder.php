<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\Tipo;

class TiposAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Tipo::class, 5)->create(['id_obra' => 1])
            ->each(function ($nivel1) {
                factory(Tipo::class, 4)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra])
                    ->each(function ($nivel2) {
                        factory(Tipo::class, 10)->create(['parent_id' => $nivel2->id, 'id_obra' => $nivel2->id_obra]);
                });
        });

        factory(Tipo::class, 5)->create(['id_obra' => 2])
            ->each(function ($nivel1) {
                factory(Tipo::class, 4)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra])
                    ->each(function ($nivel2) {
                        factory(Tipo::class, 10)->create(['parent_id' => $nivel2->id, 'id_obra' => $nivel2->id_obra]);
                });
        });

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Junior Suite']);
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD K']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD D']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF K']));
        // $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF D']));

        // $tipo = factory(Tipo::class)->create(['nombre' => 'Master Suite']);
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
