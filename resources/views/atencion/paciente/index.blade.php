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
                    <div class="col text-left">
                        <h3 class="card-title">Atenciones</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{URL::route('atenciones_reservar')}}" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-circle-plus"></i>
                            Reservar Atención
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="atenciones">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código de Atención</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Especialista</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($atenciones))
                            @foreach($atenciones as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $row->codigo_atencion }}</td>
                                    <td>{{ $row->especialidad }}</td>
                                    <td>{{ $row->especialista }}</td>
                                    <td>{{ $row->fecha }}</td>
                                    <td>{{ $row->estado }}</td>
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