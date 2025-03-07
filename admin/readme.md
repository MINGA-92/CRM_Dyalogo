<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# Manual de instalación del módulo huéspedes realizado con el framework laravel 5.8*

El modulo fue realizado mediante la versión de laravel 5.8 por lo tanto hay ciertos requisitos mínimos para que el proyecto pueda funcionar (Los siguientes datos fueron sacados de la documentación oficial de laravel).

## Requerimientos del servidor

- PHP> = 7.1.3
- Extensión PHP BCMath
- Extensión PHP Ctype
- Extensión PHP JSON
- Extensión PHP Mbstring
- Extensión PHP OpenSSL
- PDO PHP Extension
- Extensión PHP Tokenizer
- Extensión XML PHP


## Instalación

Si el proyecto a instalar proviene de un archivo .zip solo se debe descomprimir ya que tendrá todas las dependencias instaladas.

Si el proyecto a instalar proviene del repositorio de GitLab a través de esta dirección https://gitlab.com/BreinerSan/proyect_huespedes se debe realizar una instalación de dependencias mediante composer. 

**NOTA**: Para la ejecución de los comandos que se mostraran a continuación debes de estar en la ruta raíz del proyecto.

1. **Instalando dependencias con Composer**:  Se instalarán todas las dependencias necesarias para el proyecto que fueron definidas en el archivo composer.json durante el desarrollo.
Comando:
```bash
composer install
```
2. **Permisos de escritura**: Según la documentación oficial de Laravel, después de instalar Laravel, tal vez debas configurar algunos permisos, Los directorios entre storage y la carpeta bootstrap/cache deben tener permisos de escritura por el servidor web.
Comando: 
```bash
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/
chown apache:apache -R admin  // admin es el nombre del directorio
```

3. **Archivo de configuración de Laravel**: Laravel, por defecto tiene un archivo .env con los datos de configuración necesarios para el mismo, cuando utilizamos un sistema de control de versiones como git, este archivo se excluye del repositorio.
Sin embargo existe un archivo llamado  .env.example que es un ejemplo de cómo crear un el archivo de configuración.
Comando:
```bash
cp .env.example .env
```
4. **Creando un nuevo API key**: Por medidas de seguridad cada proyecto de Laravel cuenta con una clave única que se crea en el archivo .env al iniciar el proyecto, si el archivo .env no cuenta con esta clave única se puede crear al usar el siguiente comando.
```bash
php artisan key:generate
```

Una vez después de tener el proyecto, lo siguiente será configurar el archivo .env

Lo primero por hacer será modificar los siguientes valores.

    APP_ENV=production
    APP_DEBUG=false

    
5. **Creando los directorios** :
mkdir /etc/dyalogo/clientes/
mkdir /etc/dyalogo/clientes/img_huespedes/
chmod -R 777 /etc/dyalogo/clientes


**Configurar las conexiones a las bases de datos** : Para eso debes agregar las credenciales al archivo .env como se muestra en el siguiente ejemplo:

    DB_CONNECTION=general
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=dyalogo_general            #Nombre de la base de datos
    DB_USERNAME=root                             #Usuario
    DB_PASSWORD=                                   #Contraseña

    DB_CONNECTION_2=telefonia
    DB_HOST_2=127.0.0.1
    DB_PORT_2=3306
    DB_DATABASE_2=dyalogo_telefonia          #Nombre de la base de datos
    DB_USERNAME_2=root                             # Usuario
    DB_PASSWORD_2=                                  # Contraseña

    DB_CONNECTION_3=crm_sistema
    DB_HOST_3=127.0.0.1
    DB_PORT_3=3306
    DB_DATABASE_3=dyalogocrm_sistema       # Nombre de la base de datos
    DB_USERNAME_3=root                      #Usuario
    DB_PASSWORD_3=                           #Contraseña
    
    DB_CONNECTION_4=canales_electronicos
    DB_HOST_4=127.0.0.1
    DB_PORT_4=3306
    DB_DATABASE_4=dyalogo_canales_electronicos
    DB_USERNAME_4=root
    DB_PASSWORD_4=
    
    DB_CONNECTION_5=dy_sms
    DB_HOST_5=127.0.0.1
    DB_PORT_5=3306
    DB_DATABASE_5=dy_sms
    DB_USERNAME_5=root
    DB_PASSWORD_5=  

Para la puesta en marcha la url debe apuntar al directorio /public del proyecto

## Notas Adicionales
Para cambiar la ruta donde se guardan los archivos del huésped, se debe ingresar a la ruta config/filesystem.php del proyecto y cambiar la variable root con la nueva ruta.

```php
'huesped' => [
            'driver' => 'local',
            'root' => '/etc/dyalogo/clientes/img_huespedes/',           // Ruta de los archivos huesped
            'url' => env('APP_URL'),
            'visibility' => 'public',
],
```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
