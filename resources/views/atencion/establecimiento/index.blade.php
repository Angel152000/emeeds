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
                            <th scope="col">Especialidad</th>
                            <th scope="col">Especialista</th>
                            <th scope="col">Tipo de atención</th>
                            <th scope="col">Rut Paciente</th>
                            <th scope="col">Nombre Paciente</th>
                            <th scope="col">Monto Pago</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($atenciones))
                            @foreach($atenciones as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $row->codigo_atencion }}</td>
                                    <td>{{ $row->especialidad }}</td>
                                    @if(!empty($row->especialista))
                                        <td>Dr/a. {{ $row->especialista->nombres }} {{ $row->especialista->apellido_paterno }}</td>
                                    @else
                                        <td>No elegido.</td>
                                    @endif
                                    <td>{{ $row->atencion }}</td>
                                    <td>{{ $row->rut_paciente }}</td>
                                    <td>{{ $row->nombre_paciente }}</td>
                                    <td>{{ '$'.number_format($row->monto_pago,0,'.','.') }}</td>
                                    @if(isset($row->fecha))
                                        <td>{{ date("d/m/Y", strtotime($row->fecha)) }}</td>
                                    @else
                                        <td>No aplica.</td>
                                    @endif
                                    @switch($row->estado)
                                        @case(1)
                                            <td><h3><span class="badge badge-success">Realizada</span></h3></td>
                                        @break
                                        @case(2)
                                            <td><h3><span class="badge badge-primary">En Proceso</span></h3></td>
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
                            <td>Consulta/Atención realizada con éxito.</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td><h3><span class="badge badge-primary">En Proceso</span></h3></td>
                            <td>Consulta/Atención que se encuentra reservada o en proceso para ser atendido/a.</td>
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
 </script>
@stop