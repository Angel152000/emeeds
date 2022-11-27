@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">TU CUENTA DE ZOOM</h3>
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
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div style="padding:2rem!important;">
                <table id="table-zoom" class="table" style="width:100%;">
                    <thead style="background-color:#019df4;color:white;">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User Zoom</th>
                            <th scope="col">Email Zoom</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $zoom->id }}</td>
                            <td>{{ $zoom->user_zoom }}</td>
                            <td>{{ $zoom->email_zoom }}</td>
                            <td>{{ $zoom->estado }}</td>
                            <td>
                                <a href="#" onclick="reautorizar()" class="btn btn-outline-success btn-block" role="button" aria-pressed="true">RE-AUTORIZAR</a>
                                <a href="#" onclick="desvincular()" class="btn btn-outline-danger  btn-block" role="button" aria-pressed="true">DESVINCULAR</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
    $(document).ready(function() 
    {
        var table = $('#table-zoom').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay cuenta de zoom vinculada aún.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });

    function desvincular() 
    {
        Swal.fire({
            title: '¿Desea desvincular su cuenta de zoom?',
            text: 'Puede volver a registrarla nuevamente si lo desea.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3eb297',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#dc3545',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var url = '{{URL::route("desZoom")}}';
                $.ajax({
                    url: url,
                    dataType: 'json',
                    type: 'get',
                    success: function(res) 
                    {
                        if (res.status === 'success') 
                        {
                            Swal.fire({
                            title: 'Desvincular Zoom',
                            text: 'Cuenta desvinculada con exito.',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#3eb297',
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
            }
        })
    }

    function reautorizar() 
    {
        var url = '{{URL::route("reZoom")}}';
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function(res) 
            {
                if (res.status === 'success') 
                {
                    Swal.fire({
                    title: 'Reautorizar Zoom',
                    text: 'Cuenta reautorizada con exito.',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#3eb297',
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
    }
</script>
@stop