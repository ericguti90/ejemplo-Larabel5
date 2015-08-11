<?php namespace App\Http\Controllers;
 
use App\Http\Requests;
use App\Http\Controllers\Controller;
 
use Illuminate\Http\Request;
 
// Indicamos que trabajamos con Vistas
use View;
 
// Indicamos que usamos el Modelo User.
use App\User;
 
// Validación de formularios.
use Validator;
 
// Hash de contraseñas.
use Hash;
 
// Redireccionamientos.
use Redirect;

// Auth.
use Auth;
 
class UsersController extends Controller {
 
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Prueba a ver si funciona la ruta /users
        // return 'Lista de todos los usuarios';
 
        // Devolvemos una Vista con toda la lista de usuarios.
        // Usamos el método Mágico withUsers que lo que envía es una
        // variable $users que contiene todos los usuarios.
        if (!Auth::id()) return Redirect::to('login');
        return view('users')->withUsers(User::all());
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('create');
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Realizamos la validación de datos recibidos del formulario.
        $rules=array(
            'username'=>'required|unique:users', // Username es único en la tabla users
            'email'=>'required|email|unique:users', // Username es único en la tabla users
            'password'=>'required|min:6',
            'password-repeat'=>'required|same:password'
            );
 
        // Llamamos a Validator pasándole las reglas de validación.
        $validator=Validator::make($request->all(),$rules);
 
        // Si falla la validación redireccionamos de nuevo al formulario
        // enviando la variable Input (que contendrá todos los Input recibidos)
        // y la variable errors que contendrá los mensajes de error de validator.
        if ($validator->fails())
        {
            return Redirect::to('users/create')
            ->withInput()
            ->withErrors($validator->messages());
        }
 
        // Si la validación es OK, estamos listos para almacenar en la base de datos los datos.
        User::create(array(
            'username'=>$request->input('username'),
            'email'=>$request->input('email'),
            'password'=>Hash::make($request->input('password')),
            'bio'=>$request->input('bio')
            ));
 
        // Redireccionamos a users
        return Redirect::to('users');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // Se muestra la información de un usuario.
        // Comprobamos si el $id existe en la base de datos.
        $usuario=User::find($id);
 
        if ($usuario== null)
            return Redirect::to('users');
 
        return view('perfil')->withElusuario($usuario);
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // Se muestra la información de un usuario.
        // Comprobamos si el $id existe en la base de datos.
        $usuario=User::find($id);
 
        if ($usuario== null)
            return Redirect::to('users');
 
        return view('editar')->with('id',$id);
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        // Reglas de validación
        $reglas = array(
                'username' =>'unique:users',  // Deberá ser único en la tabla users
                'email' =>'email|unique:users',  // Deberá ser único en la tabla users
                'password' => 'min:6'
                );
 
        $validator= Validator::make($request->all(),$reglas);
 
        if ($validator->fails())
        {
            return Redirect::to('users/'.$id.'/edit')
            ->withInput()
            ->withErrors($validator->messages());
        }
 
        $usuario = User::find($id);
 
        if ($request->input('username'))
            $usuario->username=$request->input('username');
        if ($request->input('email'))
            $usuario->email=$request->input('email');
        if ($request->input('bio'))
            $usuario->bio=$request->input('bio');
        if ($request->input('password'))
            $usuario->password=Hash::make($request->input('password'));
 
        // Grabamos el usuario en la tabla.
        $usuario->save();
 
        // Redireccionamos a la página personal del usuario.
        return Redirect::to('users/'.$id);
    }
 
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();
 
        return Redirect::to('users');
    }
 
}