@extends('layouts.app')

@section('sub_header')
<nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
    <div clas="container">
        <h3 class="text-light font-weight-bold">CONFIGURACIÓN</h3>
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
                    <li class="breadcrumb-item active">Configuración</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Cambiar Contraseña</h3>
            </div>
            <form method="POST" id="form" action="{{URL::route('pass_paciente')}}" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Contraseña Nueva</label>
                                <input type="password" class="form-control for-especialista" id="clave" name="clave">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Repita la Contraseña</label>
                                <input type="password" class="form-control for-especialista" id="rep_clave" name="rep_clave">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
$("#form").submit(function(e)
    {
        e.preventDefault();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (res) 
            {
                if (res.status === 'success') 
                {
                    Swal.fire({
                        title: res.msg,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#019df4',
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#dc3545',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) 
                        {
                            window.location.reload();
                        }
                        else
                        {
                            window.location.reload();
                        }
                    })
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    });
</script>
@stop