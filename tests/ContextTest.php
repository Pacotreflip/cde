<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContextTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $this->inicioSesion();
        
        $this->actingAs($user)
            ->visit('/obras')
            ->click('13')
            ->click('MUSEO BARROCO')
            ->seePageIs('/');
    }
}
