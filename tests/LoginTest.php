<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $user = factory(\Ghi\Core\Models\UsuarioCadeco::class)->create();

        $this->visit('/auth/login')
            ->seePageIs('/auth/login')
            ->type($user->usuario, 'usuario')
            ->type('secret', 'clave')
            ->press('Iniciar sesión')
            ->seePageIs('/obras');
    }
}
