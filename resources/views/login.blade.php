@extends('layouts.master')
 
@section ('titulo')Login @stop
 
@section('contenido')
 
@if (Input::old())
	Error: datos de Acceso Incorrectos.
@endif
 
{!! Form::open(array('url'=>'login')) !!}
 
<p>
	{!! Form::label('username','Usuario') !!}
	{!! Form::text('username') !!}
</p>
<p>
	{!! Form::label('password','Contraseña') !!}
	{!! Form::password('password') !!}
</p>
<p>{!! Form::button('Acceder', array('class'=>'send-btn')) !!}</p>
 
{!! Form::close() !!}
 
@stop

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.send-btn').click(function(){            
    $.ajax({
      url: 'login',
      type: "post",
      data: {'username':$('input[name=username]').val(),'password':$('input[name=password]').val()},
      success: function(data){
        alert(data);
      }
    }); 
    $('.send-btn').unbind(); //unbind. to stop multiple form submit.     
  }); 
});
</script>