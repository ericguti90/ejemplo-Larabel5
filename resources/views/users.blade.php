@extends('layouts/master')
 
@section ('titulo')Usuarios @stop
 
@section('contenido')
 
	<!-- 
	Para comprobar si un usuario está autenticado, lo hacemos con Auth::check() 
	Mostramos además un enlace para desconectarse.
	-->
	@if (Auth::check())
		Usuario Actual: {{ Auth::user()->username }}. {!! Html::link('logout','Desconectar') !!}
		<hr/>
	@endif
	<h1>Listado de todos los usuarios</h1>
	@foreach($users as $user)
		<p>{{ $user->username }} (<a href='users/{{$user->id}}'>ver perfil</a>)</p>
	@endforeach
@stop