<?php

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Tipo;
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
        foreach (Obra::all() as $obra) {
            factory(Area::class, 2)->create(['id_obra' => $obra->id_obra, 'nombre' => 'Edificio'])
                ->each(function ($nivel1, $nivel1Key) {
                    $nivel1->nombre .= ' '.$nivel1Key;
                    $nivel1->save();

                    factory(Area::class, 2)->create(['parent_id' => $nivel1->id, 'id_obra' => $nivel1->id_obra, 'nombre' => 'Piso'])
                        ->each(function ($nivel2, $nivel2Key) {
                            $nivel2->nombre .= ' '.$nivel2Key;
                            $nivel2->save();

                            factory(Area::class, 10)->create([
                                'parent_id' => $nivel2->id,
                                'id_obra' => $nivel2->id_obra,
                                'nombre' => 'Habitación',
                                'tipo_id' => Tipo::where('id_obra', $nivel2->id_obra)->where('nombre', 'STD D')->first()->id,
                            ])
                                ->each(function ($nivel3, $nivel3Key) {
                                    $nivel3->nombre .= ' '.$nivel3Key;
                                    $nivel3->save();
                                });
                        });
                });
        }
    }
}
