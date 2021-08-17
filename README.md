
## Instalar herramientas básicas
Primero, debes contar con una plataforma de trabajo que tenga las herramientas básicas de Backend, tales como: Apache/Nginx, MySQL y PHP.

También pides instalar plataformas de trabajo más automatizadas, como por ejemplo:

A) Wamp

B) Xampp

C) Mamp

Recuerda revisar la versión de cada uno de los programas que incluyen. Te recomendamos como versiones mínimas: PHP 7.3 o superior, Apache 2.0, MySQL 5.6 o superior (si prefieres MariaDB, usa la versión 10.3 como mínimo).

## Instalar Composer

Para Laravel 8 sigue siendo indispensable la utilización de Composer como su manejador de dependencias, así que es necesario instalarlo antes de continuar.

Puedes aprender a instalar Composer revisando este tutorial: https://styde.net/instalacion-de-composer/.

## Instalar dependencias

Ejecutar dentro del directorio raíz el comando composer install, para descargar todas las dependencias definidas dentor del archivo composer.json.

Luego se debe generar una nueva llave publica con el comando php artisan key:generate

## Base de datos

Luego de configurar la instalación de algún gestor de base de datos como por ejemplo MySQL, se debe agregar dentro del archivo .env ubicando en la raíz del proyecto los datos para generar la conexion hacia la base de datos. Ejemplo:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=evertec

DB_USERNAME=root

DB_PASSWORD=Heme19234099


## Base de datos de prueba

Se debe crear una configuración dentro del archivo config/database.php. Ejemplo:

'testing_db' => [
    'driver' => 'mysql',
    'host' => env('TEST_DB_HOST', '127.0.0.1'),
    'database' => env('TEST_DB_DATABASE', 'laravel_testing'),
    'username' => env('TEST_DB_USERNAME', 'root'),
    'password' => env('TEST_DB_PASSWORD', 'Heme19234099'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'strict' => false,
]

Luego agregar dentro del archivo .env las variables de entorno para conectarse hacia dicha base de datos. Ejemplo


TEST_DB_HOST=127.0.0.1

TEST_DB_DATABASE=laravel_testing

TEST_DB_USERNAME=root

TEST_DB_PASSWORD=Heme19234099


## Variables de entorno

Copiar dentro del archivo .env las siguientes variables que están definidas dentro del archivo .env.example:


WEB_CHECKOUT_IP_ADDRESS=127.0.0.1 => Dirección ip Localhost

WEB_CHECKOUT_USER_AGENT="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36"

WEB_CHECKOUT_RETURN_SITE=http://everteconlinestore.dev.com/update/order/state => Url de direccionamiento del proyecto para manipular proceso de pago del producto.

TEST_PLACE_TO_PAY_URL=https://dev.placetopay.com/redirection => URL Base del servicio PlacetoPay para el procesamiento de la transacción usando Web
Checkout

TEST_PLACE_TO_PAY_LOGIN= Credencial Login para acceso a Web Checkout


TEST_PLACE_TO_PAY_SECRET_KEY= Credencial TranKey para acceso a Web Checkout

## Correr migraciones

Ejecute en el directorio raíz del proyecto el comando php artisan migrate, el comando generará la estructura de tablas definidas para el proyecto. 

## Correr migraciones  base de datos de prueba

Correr migraciones para la base de datos de prueba:

php artisan migrate --database=

El nombre de la base de datos debe ser el que se definió dentro del archivo config/app.php, para la base de datos de pruebas.

Ejemplo => php artisan migrate --database=testing_db

## Ejecutar seeding

Correr seeders para cargar los datos de prueba ejecutando: php artisan db:seed.

## Ejecución de pruebas

Puede ejecutar todas las pruebas definidas dentro de la ruta Tests\Unit utilizando el comando php artisan test.
 
También puede filtrar cada prueba pasando el nombre del método, al parámetro --filter, ejemplo:

./vendor/bin/phpunit  --filter test_create_a_new_user
