@extends('layouts.app')

@section('sub_header')
<nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
    <div clas="container">
        <h3 class="text-light font-weight-bold">COMPROBANTE DE PAGO</h3>
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
                    <li class="breadcrumb-item active">Comprobante de Pago</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="callout callout-success">
            <h5>Pago Realizado.</h5>
            <p>Espera que el especialista se contacte contigo, se te envíara el link de tele-consulta vía email.</p>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="invoice p-5 mb-3">
            <div class="row">
                <div class="col-12">
                    <h4>
                        <img src="{{ asset('vendor/adminlte/dist/img/icono1.png') }}" width="5%" alt="Responsive image"> <b>E-meeds.</b> 
                        <small class="float-right">Fecha: {{ date('d/m/Y') }}</small>
                    </h4>
                </div>

            </div>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    De
                    <address>
                        <strong>E-meeds.</strong><br>
                        Email: contacto@emeeds.cl
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    Para
                    <address>
                        <strong>{{ auth()->user()->name }}</strong><br>
                        Email: {{ auth()->user()->email }}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Bol #MA-{{ $atencion->codigo_atencion }}</b><br>
                    <br>
                    <b>Código de Atención: </b> {{ $atencion->codigo_atencion }}<br>
                    <b>ID Pago: </b> {{ $pago->payment_id }}<br>
                    <b>ID Orden: </b> {{ $pago->merchant_order_id }}<br>
                    <b>Fecha de pago: </b>{{ date("d/m/Y", strtotime($pago->fecha_pago)) }}<br>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead  class="text-light" style="background-color:#019df4">
                            <tr>
                                <th>#</th>
                                <th>Tipo de Atención</th>
                                <th>Fecha de Atención</th>
                                <th>Especialidad</th>
                                <th>Rut Especialista</th>
                                <th>Especialista</th>
                                <th>Rut Paciente</th>
                                <th>Nombre Paciente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $atencion->atencion }}</td>
                                <td>{{ date("d/m/Y", strtotime($atencion->fecha)) }}</td>
                                <td>{{ $atencion->especialidad }}</td>
                                <td>{{ $atencion->rut_espe }} </td>
                                <td>Dr/a. {{ $atencion->especialista->nombres }} {{ $atencion->especialista->apellido_paterno }}</td>
                                <td>{{ $atencion->rut_paciente }}</td>
                                <td>{{ $atencion->nombre_paciente }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">

                <div class="col-6">
                    <p class="lead">Métodos de Pago:</p>
                        <img src="{{ asset('img/mercado_pago.png') }}" alt="Responsive image" width="10%">
                    </p>
                </div>

                <div class="col-6">
                    <p class="lead">Importe Adeudado</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">Subtotal: {{ '$'.number_format($pago->monto_pago,0,'.','.') }}</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Total: {{ '$'.number_format($pago->monto_pago,0,'.','.') }}</th>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            <div class="row no-print">
                <div class="col-6 float-left">
                    <a href="{{URL::route('atenciones_pacientes')}}" class="btn btn-danger">
                        <i class="fa-solid fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
                <div class="col-6 float-right">
                    <div class="col-3 float-left">
                    </div>
                    <div class="col-3 float-right">
                        
                    </div>
                </div>
            </div>
            <div></div>
        </div>

    </div>
</div>
@stop

@section('sub_js')
@stop