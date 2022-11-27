@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">ESPECIALISTAS</h3>
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
                        <li class="breadcrumb-item active">Especialistas</li>
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
            <div class="card-header">
                <div class="row">
                    <div class="col text-left">
                        <h3 class="card-title">Mantenedor de Especialistas disponibles en el establecimiento</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{URL::route('crear_especialistas')}}" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-circle-plus"></i>
                            Agregar Especialistas
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="especialistas" style="width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Rut</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Email</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($especialistas))
                            @foreach($especialistas as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->rut}}</td>
                                    <td>{{$row->nombres}} {{$row->apellido_paterno}}</td>
                                    <td>{{$row->especialidad}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>
                                        <a  href="{{ url('/home/especialistas/editar') }}/{{$row->id}}" class="btn btn-success"><i class="fa-solid fa-pencil"></i></a>
                                        <a  onclick="eliminarEspecialista('{{$row->id}}')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <h4 class="text-center">No hay Especialistas aún, Porfavor Cree uno.</h4>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
    $(document).ready(function() {
        var table = $('#especialistas').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No Existen Especialistas aún.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });

    function eliminarEspecialista(id)
    {
        Swal.fire({
            title: 'Eliminar Especialista',
            text: 'Deseas Eliminar este Especialista ?',
            icon: 'info',
            showCancelButton: true,
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
                $.ajax({
                    url: "{{URL::route('eliminar_especialistas')}}",
                    type: 'post',
                    dataType : 'json',
                    data: {id:id},
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
                                    window.location.href = "{{URL::route('especialistas')}}";
                                }
                                else
                                {
                                    window.location.href = "{{URL::route('especialistas')}}";
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
</script>
@stop