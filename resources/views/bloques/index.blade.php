@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">BLOQUES ({{ $horarios->dia_desc }})</h3>
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
                        <li class="breadcrumb-item"><a href="{{URL::route('horarios')}}">Horarios</a></li>
                        <li class="breadcrumb-item active">Bloques - {{ $horarios->dia_desc }}</li>
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
                        <h3 class="card-title">Mantenedor de Bloques impartidos en el Horario</h3>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa-solid fa-circle-plus"></i>
                            Agregar Bloque
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="bloques">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($bloques))
                            @foreach($bloques as $row)
                                <tr>
                                    <td>{{ $row->numero_bloque }}</td>
                                    <td>{{ date("h:s", strtotime($row->hora_bloque)) }}</td>
                                    <td>{{ $row->bloque_desc }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editarBloque">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                        <a  onclick="eliminarHorario('{{$row->id_horario}}')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal Edit-->
                                <div class="modal fade" id="editarBloque" tabindex="-1"  aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Bloque</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Número de Bloque <span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="numero_bloque_{{ $row->id_bloque }}" id="numero_bloque" value="{{ $row->numero_bloque }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Hora <span style="color:red;">*</span></label>
                                                    <input class="form-control" type="time" name="hora_{{ $row->id_bloque }}" id="hora" value="{{ date('h:s', strtotime($row->hora_bloque)) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Descripción de Bloque <span style="color:red;">*</span></label>
                                                    <textarea class="form-control" name="bloque_{{ $row->id_bloque }}" id="bloque">{{ $row->bloque_desc }}</textarea>
                                                </div>
                                            </div>
                                            <input class="form-control" type="hidden" name="id_h_{{ $row->id_bloque }}" id="id_h" value="{{ $horarios->id_horario }}">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                                                <button type="button" onclick="editarBloque('{{$row->id_bloque}}')" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                        <h5 class="modal-title" id="exampleModalLabel">Agregar Bloque</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" id="form" action="{{URL::route('crear_bloque')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Número de Bloque <span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="numero_bloque" id="numero_bloque">
                            </div>
                            <div class="form-group">
                                <label>Hora <span style="color:red;">*</span></label>
                                <input class="form-control" type="time" name="hora" id="hora">
                            </div>
                            <div class="form-group">
                                <label>Descripción de Bloque <span style="color:red;">*</span></label>
                                <textarea class="form-control" name="bloque" id="bloque"></textarea>
                            </div>
                        </div>
                        <input class="form-control" type="hidden" name="id_h" id="id_h" value="{{ $horarios->id_horario }}">
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
        var table = $('#bloques').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay bloques creados para el día aún.",
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

                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    });

    function editarBloque(id) 
    {
        var url = '{{URL::route("editar_bloque")}}';
        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: {
                id:id,
                numero_bloque:$("#numero_bloque_"+id).val(),
                hora:$("#hora_"+id).val(),
                bloque:$("#bloque_"+id).val(),
                id_h:$("#id_h_"+id).val()
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

    function eliminarHorario(id) 
    {
        Swal.fire({
            title: 'Eliminar Horario',
            text: '¿Estás seguro que deseas eliminar esta Horario?, Todos los bloques que haya ingresado seran eliminados.',
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
                var url = '{{URL::route("eliminar_horario")}}';
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