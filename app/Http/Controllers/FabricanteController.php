<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Necesitaremos el modelo Fabricante para ciertas tareas.
use App\Fabricante;

// Necesitamos la clase Response para crear la respuesta especial con la cabecera de localización en el método Store()
use Response;

class FabricanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Devolverá todos los fabricantes.
        // return "Mostrando todos los fabricantes de la base de datos.";
        // return Fabricante::all();  No es lo más correcto por que se devolverían todos los registros. Se recomienda usar Filtros.
        // Se debería devolver un objeto con una propiedad como mínimo data y el array de resultados en esa propiedad.
        // A su vez también es necesario devolver el código HTTP de la respuesta.
        //php http://elbauldelprogramador.com/buenas-practicas-para-el-diseno-de-una-api-RESTful-pragmatica/
        // https://cloud.google.com/storage/docs/json_api/v1/status-codes
 
        return response()->json(['status'=>'ok','data'=>Fabricante::all()], 200);
    }

    public function ejemplo(){
        //return "Esto es un ejemplo";
        return view('welcome');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // 
        return "Se muestra formulario para crear un fabricante.";
 
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    // Pasamos como parámetro al método store todas las variables recibidas de tipo Request
    // utilizando inyección de dependencias (nuevo en Laravel 5)
    // Para acceder a Request necesitamos asegurarnos que está cargado use Illuminate\Http\Request;
    // Información sobre Request en: http://laravel.com/docs/5.0/requests 
    // Ejemplo de uso de Request:  $request->input('name');
    public function store(Request $request)
    {
 
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if (!$request->input('nombre') || !$request->input('direccion') || !$request->input('telefono'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
        }
 
        // Insertamos una fila en Fabricante con create pasándole todos los datos recibidos.
        // En $request->all() tendremos todos los campos del formulario recibidos.
        $nuevoFabricante=Fabricante::create($request->all());
 
        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        $response = Response::make(json_encode(['data'=>$nuevoFabricante]), 201)->header('Location', 'http://www.dominio.local/fabricantes/'.$nuevoFabricante->id)->header('Content-Type', 'application/json');
        return $response;
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        // return "Se muestra Fabricante con id: $id";
        // Buscamos un fabricante por el id.
        $fabricante=Fabricante::find($id);
 
        // Si no existe ese fabricante devolvemos un error.
        if (!$fabricante)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
        }
 
        return response()->json(['status'=>'ok','data'=>$fabricante],200);
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        return "Se muestra formulario para editar Fabricante con id: $id";
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Comprobamos si el fabricante que nos están pasando existe o no.
        $fabricante=Fabricante::find($id);
 
        // Si no existe ese fabricante devolvemos un error.
        if (!$fabricante)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
        }       
 
        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $direccion=$request->input('direccion');
        $telefono=$request->input('telefono');
 
        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;
 
            // Actualización parcial de campos.
            if ($nombre)
            {
                $fabricante->nombre = $nombre;
                $bandera=true;
            }
 
            if ($direccion)
            {
                $fabricante->direccion = $direccion;
                $bandera=true;
            }
 
 
            if ($telefono)
            {
                $fabricante->telefono = $telefono;
                $bandera=true;
            }
 
            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $fabricante->save();
                return response()->json(['status'=>'ok','data'=>$fabricante], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de fabricante.'])],304);
            }
        }
 
 
        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$nombre || !$direccion || !$telefono)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }
 
        $fabricante->nombre = $nombre;
        $fabricante->direccion = $direccion;
        $fabricante->telefono = $telefono;
 
        // Almacenamos en la base de datos el registro.
        $fabricante->save();
        return response()->json(['status'=>'ok','data'=>$fabricante], 200);
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // Primero eliminaremos todos los aviones de un fabricante y luego el fabricante en si mismo.
        // Comprobamos si el fabricante que nos están pasando existe o no.
        $fabricante=Fabricante::find($id);
 
        // Si no existe ese fabricante devolvemos un error.
        if (!$fabricante)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
        }       
 
        // El fabricante existe entonces buscamos todos los aviones asociados a ese fabricante.
        $aviones = $fabricante->aviones; // Sin paréntesis obtenemos el array de todos los aviones.
 
        // Comprobamos si tiene aviones ese fabricante.
        if (sizeof($aviones) > 0)
        {
            // Devolveremos un código 409 Conflict - [Conflicto] Cuando hay algún conflicto al procesar una petición, por ejemplo en PATCH, POST o DELETE.
            return response()->json(['code'=>409,'message'=>'Este fabricante posee aviones y no puede ser eliminado.'],409);
        }
 
        // Procedemos por lo tanto a eliminar el fabricante.
        $fabricante->delete();
 
        // Se usa el código 204 No Content – [Sin Contenido] Respuesta a una petición exitosa que no devuelve un body (como una petición DELETE)
        // Este código 204 no devuelve body así que si queremos que se vea el mensaje tendríamos que usar un código de respuesta HTTP 200.
        return response()->json(['code'=>204,'message'=>'Se ha eliminado el fabricante correctamente.'],204);
 
    }
}
