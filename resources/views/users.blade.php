@extends('layouts/master')
 
@section ('titulo')Usuarios @stop
 
@section('contenido')
	<h1>Listado de todos los usuarios</h1>
	@foreach($users as $user)
		<p>{{ $user->username }} (<a href='users/{{$user->id}}'>ver perfil</a>)</p>
	@endforeach
@stop