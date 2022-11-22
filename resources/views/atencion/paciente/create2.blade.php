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
                        <li class="breadcrumb-item"><a href="{{URL::route('atenciones_pacientes')}}">Atenciones</a></li>
                        <li class="breadcrumb-item active">Registrar Atención</li>
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
                        <h3 class="card-title">Selecciona a un Especialista y un Horario</h3>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resumen">
                            <i class="fas fa-search"></i>
                            Resumen de tu Atención
                        </button>
                        &nbsp;
                        <a onclick="" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-save"></i>
                            Realizar Atención
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="especialistas">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Horarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $l_conta = 1; ?> 
                        @if(!empty($especialistas))
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach($especialistas as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td> Dr/a. {{$row->nombres}} {{$row->apellido_paterno}}</td>
                                    <td>
                                    @if(!empty($bloques))
                                        @foreach($bloques as $row)
                                            <label class="btn btn-warning btn-sm">
                                                <input type="radio" name="bloque" id="bloque_{{ $l_conta }}"> {{ $row->hora_bloque }}
                                            </label>
                                            &nbsp;
                                            <?php $l_conta++; ?> 
                                        @endforeach
                                    @else
                                        <p> No hay bloques establecidos para esta especialidad. </p>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                        </div>
                        @else
                            <tr>
                                <td colspan="6">
                                    <h4 class="text-center">No Hay Especialistas disponibles para esta especialidad.</h4>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resumen">
                            <i class="fas fa-search"></i>
                            Resumen de tu Atención
                        </button>
                        &nbsp;
                        <a onclick="" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-save"></i>
                            Realizar Atención
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create-->
    <div class="modal fade" id="resumen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resumen de Atención</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label class="col-form-label">Código de Atención:</label>
                            <p>
                                {{ $atenciones->codigo_atencion }}
                            </p>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label class="col-form-label">Rut del Paciente:</label>
                            <p>
                                {{ $atenciones->rut_paciente }}
                            </p>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label class="col-form-label">Tipo de atención:</label>
                            <p>
                                {{ $tipo_atencion }}
                            </p>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label class="col-form-label">Especialidad:</label>
                            <p>
                                {{ $especialidad->nombre }}
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-form-label">Motivo de Atención:</label>
                            <p>
                                {{ $atenciones->detalle_atencion }}
                            </p>
                        </div>
                    </div>
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
        var table = $('#especialistas').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No Hay Especialistas disponibles para esta especialidad.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });
</script>
@stop