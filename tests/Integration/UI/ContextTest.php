<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContextTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $user = factory(Ghi\Core\Models\User::class)->create();
        $obra = factory(\Ghi\Core\Models\Obra::class)->create(['nombre' => 'OBRA NUEVA']);

        $this->inicioSesion()
            ->seePageIs('/obras')
            ->click($obra->nombre)
            ->seePageIs('/');
    }
}
