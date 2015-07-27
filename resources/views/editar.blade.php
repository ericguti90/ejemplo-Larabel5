@extends('layouts.master')
 
@section('titulo')Editar mi Perfil @stop 
 
@section('contenido')
 
@foreach($errors->all() as $error)
<p>{{$error}}</p>
@endforeach
 
<form method="POST" action="/users/{{$id}}">
	<p><label for="username">Nuevo Usuario: <input type="text" name="username" value=""/></label></p>
	<p><label for="email">Nuevo Email: <input type="email" name="email" value=""/></label></p>
	<p><label for="bio">Nueva Biografía: <textarea name="bio"></textarea></p>
	<p><label for="password">Nueva Contraseña:<input type="password" name="password" value=""/></label></p>
	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<input type="hidden" name="_method" value="PUT"/>
	<button>Actualizar Datos</button>
</form>
 
<hr/>
 
{!! Form::open(array('url'=>'users/'.$id,'method'=>'DELETE')) !!}
{!! Form::submit('Borrar mi Perfil') !!}
{!! Form::close() !!}
 
<br/><a href="/users/{{$id}}">Volver</a>
 
@stop