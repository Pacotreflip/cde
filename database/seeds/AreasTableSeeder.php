<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\Area;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Area::class, 2)->create(['id_obra' => 1])
            ->each(function ($nivel1) {
                factory(Area::class, 3)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra])
                    ->each(function ($nivel2) {
                        factory(Area::class, 2)->create(['parent_id' => $nivel2->id, 'id_obra' => $nivel2->id_obra]);
                });
        });

        factory(Area::class, 2)->create(['id_obra' => 2])
            ->each(function ($nivel1) {
                factory(Area::class, 3)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra])
                    ->each(function ($nivel2) {
                        factory(Area::class, 2)->create(['parent_id' => $nivel2->id, 'id_obra' => $nivel2->id_obra]);
                });
        });
    }
}
