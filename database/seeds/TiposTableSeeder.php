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
        factory(Tipo::class, 10)->create()->each(function ($t) {
            foreach (range(1, rand(1, 10)) as $i) {
                $t->subtipos()->save(factory(Subtipo::class)->make());
            }
        });
    }
}
