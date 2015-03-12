<?php

use Faker\Factory as Faker;
use Ghi\Core\Domain\Usuarios\User;
use Ghi\Core\Domain\Usuarios\UserSAO;
use Illuminate\Database\Seeder;

class UsuariosCadecoTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach (User::all() as $usuario)
		{
			$id_obra = 1;

			if ($usuario->usuario == 'ubueno') $id_obra = null;

			UserSAO::create([
				'usuario' => $usuario->usuario,
				'nombre' => $usuario->nombre,
				'id_obra' => $id_obra,
			]);
		}
	}

}
