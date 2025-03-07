<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'apikey.validate'], function(){
    Route::post('/usuarios/recordarclave', 'api\UsuarioController@recordarClave');

    // Seccion Huesped
    Route::post('/huesped/show/{id}', 'HuespedController@show');
    Route::post('/huesped/prueba-email-notificacion-smtp/{idEmail}', 'HuespedController@testCuentaNotificaciones');

    // Rutas para actualiar secciones en especifico del huesped
    Route::put('/huesped/updateShiftMesh/{id}', 'HuespedController@updateShiftMesh');
    Route::put('/huesped/updateNotifications/{id}', 'HuespedController@updateNotifications');

    // Seccion troncales
    Route::post('/huesped/estado-troncal/{id_huesped}', 'TroncalController@getEstadoTroncales');
    Route::post('/huesped/troncal/{id_troncal}', 'TroncalController@getTroncal');
    Route::put('/huesped/actualizar-troncal/{id_troncal}', 'TroncalController@updateTroncal');
    Route::delete('/huesped/eliminar-troncal/{id_troncal}', 'TroncalController@deleteTroncal');
    Route::post('/huesped/listar-troncales/{id_huesped}', 'TroncalController@getTroncales');
    Route::post('/huesped/crear-troncal/{id_huesped}', 'TroncalController@storeTroncal');
    Route::post('/huesped/eliminar-propiedad-troncal', 'TroncalController@deletePropiedadTroncal');
    Route::post('/huesped/obtener-propiedades', 'TroncalController@getPropiedades');

    // Patrones
    Route::post('/huesped/patron/{id_huesped}', 'TiposDestinoController@index');
    Route::post('/huesped/crear-patron/{id_huesped}', 'TiposDestinoController@store');
    Route::post('/huesped/actualizar-patron/{id_patron}', 'TiposDestinoController@update');
    Route::post('/huesped/eliminar-patron/{id_patron}', 'TiposDestinoController@delete');
    Route::post('/huesped/listar-patrones/{codigo}', 'TiposDestinoController@patronByPais');


    // Seccion Cuentas de correo
    Route::post('/huesped/cuenta-correo/{id}', 'CuentaCorreoController@showCuentaCorreo');
    Route::post('/huesped/listar-cuentas-correo/{id}', 'CuentaCorreoController@showAllCuentaCorreo');
    Route::post('/huesped/crear-cuenta-correo/{id}', 'CuentaCorreoController@storeCuentaCorreo');
    Route::put('/huesped/actualizar-cuenta-correo/{id}', 'CuentaCorreoController@updateCuentaCorreo');
    Route::delete('/huesped/eliminar-cuenta-correo/{id}', 'CuentaCorreoController@deleteCuentaCorreo');
    Route::post('/huesped/test-correo-send-mail-service', 'CuentaCorreoController@testSendMailService');
    Route::post('/huesped/test-correo-in', 'CuentaCorreoController@testEntrada');

    // Proveedor Sms
    Route::post('/huesped/proveedor-sms/{id}', 'ProveedorSmsController@ShowProveedorSms');
    Route::post('/huesped/listar-proveedores-sms/{id_huesped}', 'ProveedorSmsController@showAllProveedorSms');
    Route::post('/huesped/crear-proveedor-sms/{id_huesped}', 'ProveedorSmsController@storeProveedorSms');
    Route::put('/huesped/actualizar-proveedor-sms/{id}', 'ProveedorSmsController@updateProveedorSms');
    Route::delete('/huesped/eliminar-proveedor-sms/{id}', 'ProveedorSmsController@deleteProveedorSms');
    Route::post('/huesped/prueba-proveedor-sms', 'ProveedorSmsController@pruebaEnviarSms');


    // Seccion de definicion de webservices
    Route::post('/huesped/webservice-save/{id_huesped}', 'WebserviceController@store');
    Route::post('/huesped/webservice/{id_huesped}', 'WebserviceController@index');
    Route::post('/huesped/webservice-data/{id}', 'WebserviceController@show');
    Route::post('/huesped/webservice-borrar/{id}', 'WebserviceController@delete');
    Route::post('/huesped/webservice-borrar-header/{id}', 'WebserviceController@deleteHeader');
    Route::post('/huesped/webservice-borrar-parametro/{id}', 'WebserviceController@deleteParametro');
    Route::post('/huesped/webservice-borrar-parametro-retorno/{id}', 'WebserviceController@deleteParametroRetorno');


    // Seccion de canal de whatsapp
    Route::post('/huesped/whatsapp-all/{id_huesped}', 'WhatsappController@index');
    Route::post('/huesped/whatsapp/{id}', 'WhatsappController@show');
    Route::post('/huesped/crear-whatsapp/{id_huesped}', 'WhatsappController@store');
    Route::put('/huesped/actualizar-whatsapp/{id}', 'WhatsappController@update');
    Route::delete('/huesped/eliminar-whatsapp/{id}', 'WhatsappController@delete');


    // Seccion definicion de plantillas whatsapp
    Route::post('/huesped/plantilla-w-save/{id_huesped}', 'WhatsappPlantillaController@store');
    Route::post('/huesped/plantillas-wa/{id_huesped}', 'WhatsappPlantillaController@index');
    Route::post('/huesped/plantilla-wa-data/{id}', 'WhatsappPlantillaController@show');
    Route::delete('/huesped/plantilla-wa-delete/{id}', 'WhatsappPlantillaController@delete');


    // Seccion de facebook
    Route::post('/huesped/facebook-all/{id_huesped}', 'FacebookController@index');
    Route::post('/huesped/facebook/{id}', 'FacebookController@show');
    Route::post('/huesped/crear-facebook/{id_huesped}', 'FacebookController@store');
    Route::put('/huesped/actualizar-facebook/{id}', 'FacebookController@update');
    Route::delete('/huesped/eliminar-facebook/{id}', 'FacebookController@delete');


    // Festivos y dias no festivos
    Route::post('/huesped/listar-festivos/{id_huesped}', 'FestivosController@listarFestivos');
    Route::post('/huesped/guardar-festivos/{id_huesped}', 'FestivosController@storeFestivos');
    Route::post('/huesped/generar-festivos/{id_huesped}', 'FestivosController@generarFestivos');

    // Seccion definicion de instagram
    Route::post('/huesped/crear-instagram/{id_huesped}', 'InstagramController@store');
    Route::put('/huesped/actualizar-instagram/{id}', 'InstagramController@update');
    Route::post('/huesped/all-instagram/{id}', 'InstagramController@index');
    Route::post('/huesped/instagram/{id}', 'InstagramController@show');
    Route::post('/huesped/eliminar-instagram/{id}', 'InstagramController@delete');

});

Route::post('/channel/test/w/in', 'api\TestCanalController@inMessage');
Route::post('/channel/test/w/out/{id}', 'api\TestCanalController@outMessage');
Route::post('/channel/test/w/activate/{id}', 'api\TestCanalController@activate');
Route::post('/channel/test/w/deactivate/{id}', 'api\TestCanalController@deactivate');
Route::post('/channel/test/w/send', 'api\TestCanalController@enviarTest');

Route::post('/channel/test/f/in', 'api\TestCanalController@inMessageF');
Route::post('/channel/test/f/out/{id}', 'api\TestCanalController@outMessageF');
Route::post('/channel/test/f/activate/{id}', 'api\TestCanalController@activateF');
Route::post('/channel/test/f/deactivate/{id}', 'api\TestCanalController@deactivateF');
Route::post('/channel/test/f/send', 'api\TestCanalController@enviarTestF');
