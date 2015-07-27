@extends('layouts.master')
 
@section('titulo')Regístrate @stop 
 
@section('contenido')
 
@foreach($errors->all() as $error)
<p>{{$error}}</p>
@endforeach
 
<form method="POST" action="/FabricanteAviones/public/users">
	<p><label for="username">Usuario*: <input type="text" name="username" value="{{ Input::old('username') }}"/></label></p>
	<p><label for="email">E-mail*: <input type="email" name="email" value="{{ Input::old('email') }}"/></label></p>
	<p><label for="bio">Biografía: <textarea name="bio">{{ Input::old('bio') }}</textarea></p>
	<p><label for="password">Contraseña*:<input type="password" name="password" value="{{ Input::old('password') }}"/></label></p>
	<p><label for="password-repeat">Repita Contraseña*:<input type="password" name="password-repeat" value="{{ Input::old('password-repeat') }}"/></label></p>
	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<button>Submit</button>
</form>

{!! Form::open(array('url'=> '/users','method'=>'POST')) !!}
<p>
    {!! Form::label('username','Usuario*:') !!}
    {!! Form::text('username', Input::old('username')) !!}
</p>
<p>
    {!! Form::label('email','E-mail*:') !!}
    {!! Form::email('email', Input::old('email')) !!}
</p>
<p>
    {!! Form::label('bio','Biografía:') !!}
    {!! Form::textarea('bio', Input::old('bio')) !!}
</p>
<p>
    {!! Form::label('password','Contraseña*:') !!}
    {!! Form::password('password') !!}
</p>
<p>
    {!! Form::label('password-repeat','Repita Contraseña*:') !!}
    {!! Form::password('password-repeat') !!}
</p>
	{!! Form::hidden('_token', csrf_token())!!}
<p>
	{!! Form::submit('Submit') !!}
</p>
{!! Form::close() !!}
@stop