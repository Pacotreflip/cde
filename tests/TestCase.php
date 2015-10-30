<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://control-equipamiento.dev';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function inicioSesion(array $overrides = ['clave' => 'secret'])
    {
        $user = factory(Ghi\Core\Models\User::class)->create($overrides);

        $this->visit('/')
            ->seePageIs('/auth/login')
            ->type($user->usuario, 'usuario')
            ->type($overrides['clave'], 'clave')
            ->press('Iniciar sesión');

        return $this;
    }

    public function seleccionoObra(array $overrides = [])
    {
        $obra = factory(\Ghi\Core\Models\Obra::class)->create($overrides);

        $this->visit('/obras')
            ->click($obra->nombre);

        return $this;
    }
}
