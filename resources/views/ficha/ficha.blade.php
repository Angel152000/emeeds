@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">FICHA MÉDICA</h3>
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
                        <li class="breadcrumb-item active">Ficha Médica</li>
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
                        <h3 class="card-title">Notas de tu Ficha médica.</h3>
                    </div>
                    <div class="col text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="pacientes" style="width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Rut Paciente</th>
                            <th scope="col">Tipo de Atención</th>
                            <th scope="col">Fecha de Atención</th>
                            <th scope="col">Especialista</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($fichas))
                            @foreach($fichas as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->rut_paciente }}</td>
                                    <td>{{ $row->atencion }}</td>
                                    <td>{{ $row->fecha_at }}</td>
                                    <td>{{ $row->especialista }}</td>
                                    <td>{{ $row->especialidad }}</td>
                                    <td>
                                        {{ $row->nota }}
                                    </td>
                                </tr>
                            @endforeach
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
        var table = $('#pacientes').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay atenciones realizadas o en proceso.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });
</script>
@stop