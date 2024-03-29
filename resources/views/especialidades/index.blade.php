@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">ESPECIALIDADES</h3>
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
                        <li class="breadcrumb-item active">Especialidades</li>
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
                        <h3 class="card-title">Mantenedor de Especialidades impartidas en el establecimiento</h3>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa-solid fa-circle-plus"></i>
                            Agregar Especialidades
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="especialidades" style="width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Costo de Atención</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($especialidades))
                            @foreach($especialidades as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $row->codigo }}</td>
                                    <td>{{ $row->nombre }}</td>
                                    <td>{{ '$'.number_format($row->costo,0,'.','.') }}</td>
                                    <td>{{ $row->descripcion }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editarEspecialidad_{{$row->id}}">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                        <a  onclick="eliminarEspecialidad('{{$row->id}}')" class="btn btn-danger" title="Eliminar Servicio"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal Edit-->
                                <div class="modal fade" id="editarEspecialidad_{{$row->id}}" tabindex="-1" aria-labelledby="editarEspecialidad" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Especialidades</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="codigo" class="col-form-label">Código <span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="codigo_{{$row->id}}" name="codigo" value="{{ $row->codigo }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="nombre" class="col-form-label">Nombre <span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="nombre_{{$row->id}}" name="nombre" value="{{ $row->nombre }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="costo" class="col-form-label">Costo de Atención <span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="costo_{{$row->id}}" name="costo" value="{{ str_replace('$.',' ',$row->costo) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción <span style="color:red;">*</span></label>
                                                    <textarea class="form-control" name="descripcion" id="descripcion_{{$row->id}}">{{ $row->descripcion }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                                                <button type="button" onclick="editarEspecialidad('{{$row->id}}')" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <h4 class="text-center">No hay Especialidades aún, Porfavor Cree una.</h4>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Create-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar Especialidades</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" id="form" action="{{URL::route('crear_especialidades')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="codigo" class="col-form-label">Código <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="codigo" name="codigo">
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-form-label">Nombre <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            <div class="form-group">
                                <label for="costo" class="col-form-label">Costo de Atención <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="costo" name="costo">
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="col-form-label">Descripción <span style="color:red;">*</span></label>
                                <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
    $(document).ready(function() {
        var table = $('#especialidades').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No Existen Especialidades aún.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });

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

                    $("#codigo").val('');
                    $("#nombre").val('');
                    $("#descripcion").val('');
                    $("#costo").val('');
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    });
    
    function editarEspecialidad(id) 
    {
        var url = '{{URL::route("editar_especialidades")}}';
        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: {
                id:          id,
                codigo:      $("#codigo_"+id).val(),
                nombre:      $("#nombre_"+id).val(),
                costo:       $("#costo_"+id).val(),
                descripcion: $("#descripcion_"+id).val()
            },
            success: function(res) 
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
    }

    function eliminarEspecialidad(id) 
    {
        Swal.fire({
            title: 'Eliminar Especialidad',
            text: '¿Estás seguro que deseas eliminar esta especialidad?',
            icon: 'warning',
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
                var url = '{{URL::route("eliminar_especialidades")}}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType : 'json',
                    data: {
                        id: id,
                    },
                    success: function(res) 
                    {
                        console.log(res);
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
            }
        })
    }
</script>
@stop