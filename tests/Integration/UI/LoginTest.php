<?php

use Ghi\Core\Models\User;
use Ghi\Core\Models\UsuarioCadeco;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $user = factory(User::class)->create();

        $this
            ->inicioSesion()
            ->seePageIs('/obras');
    }
}
