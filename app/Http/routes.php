<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('fabricantes', [
	'as' => 'fabric', 
	'uses' => 'FabricanteController@ejemplo']
);
Route::group(array('prefix'=>'api/v1.0'),function() {
	// resource recibe nos parámetros(URI del recurso, Controlador que gestionará las peticiones)
Route::resource('fabricantes','FabricanteController',['except'=>['edit','create'] ]);	// Todos los métodos menos Edit que mostraría un formulario de edición.
 
// Si queremos dar  la funcionalidad de ver todos los aviones tendremos que crear una ruta específica.
// Pero de aviones solamente necesitamos solamente los métodos index y show.
// Lo correcto sería hacerlo así:
Route::resource('aviones','AvionController',[ 'only'=>['index','show'] ]); // El resto se gestionan en FabricanteAvionController
 
// Como la clase principal es fabricantes y un avión no se puede crear si no le indicamos el fabricante, 
// entonces necesitaremos crear lo que se conoce como  "Recurso Anidado" de fabricantes con aviones.
// Definición del recurso anidado:
Route::resource('fabricantes.aviones','FabricanteAvionController',[ 'except'=>['show','edit','create'] ]);
});

 
/*
php artisan route:list 
 
+--------+----------+---------------------------------------------+-----------------------------+--------------------------------------------------------+------------+
| Domain | Method   | URI                                         | Name                        | Action                                                 | Middleware |
+--------+----------+---------------------------------------------+-----------------------------+--------------------------------------------------------+------------+
|        | GET|HEAD | fabricantes                                 | fabricantes.index           | App\Http\Controllers\FabricanteController@index        |            |
|        | POST     | fabricantes                                 | fabricantes.store           | App\Http\Controllers\FabricanteController@store        | auth.basic |
|        | GET|HEAD | fabricantes/{fabricantes}                   | fabricantes.show            | App\Http\Controllers\FabricanteController@show         |            |
|        | PUT      | fabricantes/{fabricantes}                   | fabricantes.update          | App\Http\Controllers\FabricanteController@update       | auth.basic |
|        | PATCH    | fabricantes/{fabricantes}                   |                             | App\Http\Controllers\FabricanteController@update       | auth.basic |
|        | DELETE   | fabricantes/{fabricantes}                   | fabricantes.destroy         | App\Http\Controllers\FabricanteController@destroy      | auth.basic |
|        | GET|HEAD | aviones                                     | aviones.index               | App\Http\Controllers\AvionController@index             |            |
|        | GET|HEAD | aviones/{aviones}                           | aviones.show                | App\Http\Controllers\AvionController@show              |            |
|        | GET|HEAD | fabricantes/{fabricantes}/aviones           | fabricantes.aviones.index   | App\Http\Controllers\FabricanteAvionController@index   |            |
|        | POST     | fabricantes/{fabricantes}/aviones           | fabricantes.aviones.store   | App\Http\Controllers\FabricanteAvionController@store   | auth.basic |
|        | PUT      | fabricantes/{fabricantes}/aviones/{aviones} | fabricantes.aviones.update  | App\Http\Controllers\FabricanteAvionController@update  | auth.basic |
|        | PATCH    | fabricantes/{fabricantes}/aviones/{aviones} |                             | App\Http\Controllers\FabricanteAvionController@update  | auth.basic |
|        | DELETE   | fabricantes/{fabricantes}/aviones/{aviones} | fabricantes.aviones.destroy | App\Http\Controllers\FabricanteAvionController@destroy | auth.basic |
+--------+----------+---------------------------------------------+-----------------------------+--------------------------------------------------------+------------+
*/

// Cuando usamos un controlador resource tenemos que implementar todos los métodos
// index, store, etc.. Aunque se pueden indicar en la ruta cuales no queremos con except
Route::resource('users','UsersController');
// Creamos un Controlador para gestionar la autenticación en HomeController.
Route::controller('/','HomeController');