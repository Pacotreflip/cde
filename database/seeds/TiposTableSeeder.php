<?php

use Ghi\Subtipo;
use Ghi\Tipo;
use Illuminate\Database\Seeder;

class TiposTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(Tipo::class, 10)->create()->each(function ($t) {
//            foreach (range(1, rand(1, 10)) as $i) {
//                $t->subtipos()->save(factory(Subtipo::class)->make());
//            }
//        });

        $tipo = factory(Tipo::class)->create(['nombre' => 'Junior Suite']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD K']));
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'STD D']));
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF K']));
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF D']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Master Suite']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Family Suite']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Villa']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Wedding Suite']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Family Presidential']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

        $tipo = factory(Tipo::class)->create(['nombre' => 'Presidential Suite']);
        $tipo->subtipos()->save(factory(Subtipo::class)->make(['nombre' => 'PREF']));

    }
}
