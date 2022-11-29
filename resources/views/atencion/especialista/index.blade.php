@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">ATENCIONES</h3>
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
                        <li class="breadcrumb-item active">Atenciones</li>
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
                    <div class="col text-left"></div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#leyenda">
                            <i class="fas fa-search"></i>
                            Leyenda de Estados
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="atenciones" style="width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código de Atención</th>
                            <th scope="col">Tipo de atención</th>
                            <th scope="col">Rut Paciente</th>
                            <th scope="col">Nombre Paciente</th>
                            <th scope="col">Motivo de Atención</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado </th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($atenciones))
                            @foreach($atenciones as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $row->codigo_atencion }}</td>
                                    <td>{{ $row->atencion }}</td>
                                    <td>{{ $row->rut_paciente }}</td>
                                    <td>{{ $row->nombre_paciente }}</td>
                                    <td>{{ $row->detalle_atencion }}</td>
                                    @if(isset($row->fecha))
                                        <td>@php echo date("d/m/Y", strtotime($row->fecha)); @endphp</td>
                                    @else
                                        <td>No aplica.</td>
                                    @endif
                                    @switch($row->estado)
                                        @case(1)
                                            <td><h3><span class="badge badge-success">Realizada</span></h3></td>
                                            <td></td>
                                        @break
                                        @case(2)
                                            <td><h3><span class="badge badge-primary">Reservada</span></h3></td>
                                            <td>
                                                @if($row->atencion_zoom == 0)
                                                    <a  onclick="reenviarPaciente('{{ $row->id_atencion }}')" class="btn btn-primary"><i class="fa-solid fa-video "></i></a>
                                                    <a  href="{{ $row->link_atencion }}" target="_blank" class="btn btn-info"><i class="fa-solid fa-circle-play"></i></a>
                                                    <a  onclick="cambiarEstado('{{ $row->id_atencion }}')" class="btn btn-success"><i class="fa-solid fa-laptop-medical "></i></a>
                                                @else
                                                    <a  onclick="contactarPaciente('{{ $row->id_atencion }}')" class="btn btn-primary"><i class="fa-solid fa-video "></i></a>
                                                    <a  onclick="cancelarAtencion('{{ $row->id_atencion }}')" class="btn btn-danger"><i class="fa-solid fa-laptop-medical "></i></a>
                                                @endif
                                            </td>
                                        @break
                                        @case(5)
                                            <td><h3><span class="badge badge-danger">Cancelada</span></h3></td>
                                            <td>

                                            </td>
                                        @break
                                    @endswitch
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal leyenda-->
<div class="row">
    <div class="modal fade bd-leyenda-lg col-xs-12 col-sm-12 col-md-12 col-lg-12" id="leyenda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Leyenda de Estados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <table class="table table-responsive">
                    <caption>Leyenda para los estados de la atención</caption>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td><h3><span class="badge badge-success">Realizada</span></h3></td>
                            <td>Consulta/Atención realizada.</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td><h3><span class="badge badge-primary">Reservada</span></h3></td>
                            <td>Consulta/Atención que se encuentra cancelada por el paciente.</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>
                                <h3><span class="badge badge-danger">Cancelada</span></h3>
                            </td>
                            <td>Consulta/Atención que se encuentra cancelada por el especialista, este estado se presenta a través de posibles inconvenientes para la realización de la misma.</td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_js')
 <script>
    $(document).ready(function() {
        var table = $('#atenciones').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay atenciones realizadas o por realizar aún.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });

    function contactarPaciente(id) 
    {
        Swal.fire({
            title: '¿Estás seguro que deseas contactar al Paciente?',
            text: 'se les envíara un correo para ambos indicando el link de la reunión en la cual se tienen que unir, además a usted se le habilitará un botón en la plataforma para que se pueda unir directamente.',
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
                var url = '{{URL::route("atencion_contactar")}}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType : 'json',
                    data: {
                        id: id,
                    },
                    beforeSend: function()
                    {
                        Swal.fire({
                            title: 'Cargando...',
                            html: 'Espere mientras procesamos la solicitud.',
                            timerProgressBar: true,
                            showCloseButton: false,
                            showCancelButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    success: function(res) 
                    {
                        swal.close();

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

    function reenviarPaciente(id) 
    {
        Swal.fire({
            title: '¿Estás seguro que deseas reenviar el correo al paciente?',
            text: 'se les envíara un correo para ambos indicando el nuevo link de la reunión en la cual se tienen que unir, además a usted se le habilitará el nuevo link en el mismo botón en la plataforma para que se pueda unir directamente.',
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
                var url = '{{URL::route("atencion_reenviar")}}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType : 'json',
                    data: {
                        id: id,
                    },
                    beforeSend: function()
                    {
                        Swal.fire({
                            title: 'Cargando...',
                            html: 'Espere mientras procesamos la solicitud.',
                            timerProgressBar: true,
                            showCloseButton: false,
                            showCancelButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    success: function(res) 
                    {
                        swal.close();
                        
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

    function cambiarEstado(id) 
    {
        Swal.fire({
            title: '¿Estás seguro que deseas cambiar el estado de la reunión a realizada?',
            text: 'Esta acción se realiza una vez este terminada la atención médica.',
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
                var url = '{{URL::route("atencion_estado")}}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType : 'json',
                    data: {
                        id: id,
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
        })
    }

    function cancelarAtencion(id) 
    {
        Swal.fire({
            title: '¿Estás seguro que deseas cancelar la atención?',
            text: 'Esta acción es irreversible, se le notificará al paciente vía correo de la cancelación de la reunión.',
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
                var url = '{{URL::route("atencion_cancelar")}}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType : 'json',
                    data: {
                        id: id,
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
        })
    }
 </script>
@stop