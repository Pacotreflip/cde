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

    public function inicioSesion($usuario = 'test', $clave = 'secret')
    {
        $user = factory(\Ghi\Core\Models\UsuarioCadeco::class)->create([
            'usuario' => $usuario, 'clave' => $clave
        ]);

        $this->visit('/')
            ->seePageIs('/auth/login')
            ->type($usuario, 'usuario')
            ->type($clave, 'clave')
            ->press('Iniciar sesión');

        return $this;
    }
}
