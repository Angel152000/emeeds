@extends('layouts.app')

@section('sub_header')
<nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
  <div clas="container">
    <h3 class="text-light font-weight-bold">CONECTA CON TU CUENTA DE ZOOM</h3>
  </div>
</nav>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{URL::route('home')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Zoom</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@stop

@section('sub_content')
<div class="row text-center">
  @if ($cuenta == 'NOCONECTADO')
  <div class="alert alert-danger col-xs-12 col-sm-12 col-md-12 col-lg-12" role="alert">
    No se pudo conectar con la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (auth:001).
  </div>
  @endif
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <img src="{{ asset('img/zoomapi.png') }}" width="800" height="600" class="img-fluid" alt="Responsive image">
  </div>
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
    <form method="POST" id="form" action="{{URL::route('authZoom')}}">
      {{csrf_field()}}
      <input type="hidden" id="conect" value="1">
      <button type="submit" class="btn btn-outline-primary btn-lg" style="width:50%;">CONECTAR TU CUENTA</button>
  </div>
</div>
@stop

@section('sub_js')
<script>
  $('#form').submit(function(e) {
    e.preventDefault();
    var url = $(this).attr('action');

    $.ajax({
      url: url,
      type: 'post',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(res) {
        if (res.status === 'success') {
          $(location).attr('href', res.url);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {

      },
      complete: function() {

      }
    });

  });
</script>
@stop