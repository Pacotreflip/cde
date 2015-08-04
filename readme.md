## GHI SAO Maquinaria

Esta aplicacion permite administrar el reporte y conciliacion de la operacion de maquinaria.

## How to install

1. Crear un archivo .env con la configuración para base de datos

2. Correr las migraciones de intranet (solo en caso de no tener una base de datos de pruebas)

3. Correr los seeders de intranet (solo en caso de no tener una base de datos de pruebas)

4. Correr las migraciones de cadeco:

   php artisan migrate --database=CONEXION_BD --path=database/migrations/cadeco

5. Correr los seeders de cadeco (solo en caso de no tener una base de datos de pruebas):

   php artisan db:seed --database=CONEXION_BD --class=CadecoDatabaseSeeder

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)