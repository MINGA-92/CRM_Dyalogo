<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/huesped/search', 'HuespedController@search')->name('huesped.search');
    Route::get('/huesped/file/{id}/{tipo}', 'HuespedController@getFile');
    Route::post('/huesped/add-new-contacto', 'HuespedController@addNewContacto');
    // Notificaciones Internas
    Route::post('/huesped/prueba-email-notificacion-smtp/{idEmail}', 'HuespedController@testCuentaNotificaciones');



    // Seccion troncales
    Route::get('/huesped/estado-troncal/{id_huesped}', 'TroncalController@getEstadoTroncales');
    Route::get('/huesped/listar-troncales/{id_huesped}', 'TroncalController@getTroncales');
    Route::get('/huesped/troncal/{id_troncal}', 'TroncalController@getTroncal');
    Route::post('/huesped/crear-troncal/{id_huesped}', 'TroncalController@storeTroncal');
    Route::put('/huesped/actualizar-troncal/{id_troncal}', 'TroncalController@updateTroncal');
    Route::delete('/huesped/eliminar-troncal/{id_troncal}', 'TroncalController@deleteTroncal');
    Route::post('/huesped/eliminar-propiedad-troncal', 'TroncalController@deletePropiedadTroncal');
    Route::post('/huesped/obtener-propiedades', 'TroncalController@getPropiedades');


    // Patrones
    Route::get('/huesped/patron/{id_huesped}', 'TiposDestinoController@index');
    Route::post('/huesped/crear-patron/{id_huesped}', 'TiposDestinoController@store');
    Route::post('/huesped/actualizar-patron/{id_patron}', 'TiposDestinoController@update');
    Route::post('/huesped/eliminar-patron/{id_patron}', 'TiposDestinoController@delete');
    Route::get('/huesped/listar-patrones/{codigo}', 'TiposDestinoController@patronByPais');


    // Seccion Cuentas de correo
    Route::get('/huesped/cuenta-correo/{id}', 'CuentaCorreoController@showCuentaCorreo');
    Route::get('/huesped/listar-cuentas-correo/{id}', 'CuentaCorreoController@showAllCuentaCorreo');
    Route::post('/huesped/crear-cuenta-correo/{id}', 'CuentaCorreoController@storeCuentaCorreo')->name('canal.cuentaCorreo');
    Route::put('/huesped/actualizar-cuenta-correo/{id}', 'CuentaCorreoController@updateCuentaCorreo');
    Route::delete('/huesped/eliminar-cuenta-correo/{id}', 'CuentaCorreoController@deleteCuentaCorreo');
    Route::post('/huesped/test-correo-send-mail-service', 'CuentaCorreoController@testSendMailService');
    Route::post('/huesped/test-correo-in', 'CuentaCorreoController@testEntrada');


    // Proveedor Sms
    Route::get('/huesped/proveedor-sms/{id}', 'ProveedorSmsController@ShowProveedorSms');
    Route::get('/huesped/listar-proveedores-sms/{id_huesped}', 'ProveedorSmsController@showAllProveedorSms');
    Route::post('/huesped/crear-proveedor-sms/{id_huesped}', 'ProveedorSmsController@storeProveedorSms');
    Route::put('/huesped/actualizar-proveedor-sms/{id}', 'ProveedorSmsController@updateProveedorSms');
    Route::delete('/huesped/eliminar-proveedor-sms/{id}', 'ProveedorSmsController@deleteProveedorSms');
    Route::post('/huesped/prueba-proveedor-sms', 'ProveedorSmsController@pruebaEnviarSms');


    // Seccion de definicion de webservices
    Route::post('/huesped/webservice-save/{id_huesped}', 'WebserviceController@store');
    Route::get('/huesped/webservice/{id_huesped}', 'WebserviceController@index');
    Route::get('/huesped/webservice-data/{id}', 'WebserviceController@show');
    Route::post('/huesped/webservice-borrar/{id}', 'WebserviceController@delete');
    Route::post('/huesped/webservice-borrar-header/{id}', 'WebserviceController@deleteHeader');
    Route::post('/huesped/webservice-borrar-parametro/{id}', 'WebserviceController@deleteParametro');
    Route::post('/huesped/webservice-borrar-parametro-retorno/{id}', 'WebserviceController@deleteParametroRetorno');


    // Seccion de canal de whatsapp
    Route::get('/huesped/whatsapp-all/{id_huesped}', 'WhatsappController@index');
    Route::get('/huesped/whatsapp/{id}', 'WhatsappController@show');
    Route::post('/huesped/crear-whatsapp/{id_huesped}', 'WhatsappController@store');
    Route::put('/huesped/actualizar-whatsapp/{id}', 'WhatsappController@update');
    Route::delete('/huesped/eliminar-whatsapp/{id}', 'WhatsappController@delete');


    // Seccion definicion de plantillas whatsapp
    Route::post('/huesped/plantilla-w-save/{id_huesped}', 'WhatsappPlantillaController@store');
    Route::get('/huesped/plantillas-wa/{id_huesped}', 'WhatsappPlantillaController@index');
    Route::get('/huesped/plantilla-wa-data/{id}', 'WhatsappPlantillaController@show');
    Route::delete('/huesped/plantilla-wa-delete/{id}', 'WhatsappPlantillaController@delete');


    // Seccion de facebook
    Route::get('/huesped/facebook-all/{id_huesped}', 'FacebookController@index');
    Route::get('/huesped/facebook/{id}', 'FacebookController@show');
    Route::post('/huesped/crear-facebook/{id_huesped}', 'FacebookController@store');
    Route::put('/huesped/actualizar-facebook/{id}', 'FacebookController@update');
    Route::delete('/huesped/eliminar-facebook/{id}', 'FacebookController@delete');


    // Festivos y dias no festivos
    Route::get('/huesped/listar-festivos/{id_huesped}', 'FestivosController@listarFestivos');
    Route::post('/huesped/guardar-festivos/{id_huesped}', 'FestivosController@storeFestivos');
    Route::post('/huesped/generar-festivos/{id_huesped}', 'FestivosController@generarFestivos');


    // Seccion definicion de instagram
    Route::post('/huesped/crear-instagram/{id_huesped}', 'InstagramController@store');
    Route::put('/huesped/actualizar-instagram/{id}', 'InstagramController@update');
    Route::get('/huesped/all-instagram/{id}', 'InstagramController@index');
    Route::get('/huesped/instagram/{id}', 'InstagramController@show');
    Route::post('/huesped/eliminar-instagram/{id}', 'InstagramController@delete');


    // El recursos debe de ir de ultimas para no solapar otras rutas
    Route::resource('/huesped', 'HuespedController');


});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pais/ciudad/{id}', 'PaisCiudadController@getCiudad');
Route::get('/pais/ciudades/{pais}', 'PaisCiudadController@getCiudadPais');
Route::get('/pais', 'PaisCiudadController@getPais');



// Pausas
Route::get('/huesped/listar-pausas/{id_huesped}', 'PausaController@listarPausas');
Route::post('/huesped/guardar-pausas/{id_huesped}', 'PausaController@storePausa');



// Mail notificaciones internas
//Route::get('/huesped/mail-notificaciones-internas/{id_huesped}', 'MailNotificacionesController@showMailNotificacion');
//Route::post('/huesped/registrar-mail-notificaciones/{id_huesped}', 'MailNotificacionesController@insertarMailNotificacion');




// Seccion usuarios
Route::get('/huesped/listar-usuarios/{id_huesped}', 'UsuarioController@getUsuarios');
Route::get('/listar-usuarios-admin/{id_huesped}', 'UsuarioController@getUsuariosDyalogo');
Route::post('/huesped/asignar-usuario/{id_huesped}', 'UsuarioController@asignarUsuario');
Route::post('/huesped/crear-usuario/{id_huesped}', 'UsuarioController@storeUsuario');
Route::delete('/huesped/eliminar-usuario/{id_usuario}', 'UsuarioController@desvincularUsuario');


// Route::post('/huesped/webservice-borrar/{id}', 'WhatsappPlantillaController@delete');
// Route::post('/huesped/webservice-borrar-header/{id}', 'WhatsappPlantillaController@deleteHeader');
// Route::post('/huesped/webservice-borrar-parametro/{id}', 'WhatsappPlantillaController@deleteParametro');
// Route::post('/huesped/webservice-borrar-parametro-retorno/{id}', 'WhatsappPlantillaController@deleteParametroRetorno');
