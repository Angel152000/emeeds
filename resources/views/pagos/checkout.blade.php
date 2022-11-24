@extends('layouts.app')

<?php
    // SDK de Mercado Pago
    require base_path('vendor/autoload.php');

    // Agrega credenciales
    MercadoPago\SDK::setAccessToken(config('services.mercado_pago.token'));

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();

    $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->id = $atencion->codigo_atencion;
    $item->title = 'Atención Medica';
    $item->quantity = 1;
    $item->unit_price = 20;//$atencion->costo;
    $item->currency_id = "CLP";
    $preference->items = array($item);

    //Urls direccionamiento.
    $preference->back_urls = array(
        "success" => $protocol.$_SERVER['HTTP_HOST']."/home/atenciones/pago/checkout/success/pago/".$atencion->codigo_atencion,
        "failure" => $protocol.$_SERVER['HTTP_HOST']."/home/atenciones/pago/checkout/success/pago/".$atencion->codigo_atencion,
        "pending" => $protocol.$_SERVER['HTTP_HOST']."/home/atenciones/pago/checkout/success/pago/".$atencion->codigo_atencion
    );
    
    $preference->auto_return = "approved";
    $preference->binary_mode = true;

    $preference->save();
?>

@section('sub_header')
<nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
    <div clas="container">
        <h3 class="text-light font-weight-bold">CHECKOUT</h3>
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
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_content')
<div class="col-12">

    <div class="invoice p-5 mb-3">
        <div class="row">
            <div class="col-12">
                <h4>
                    <img src="{{ asset('vendor/adminlte/dist/img/icono1.png') }}" width="5%"> <b>E-meeds.</b> 
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
                <b>Fecha de pago: </b>{{ date('d/m/Y') }}<br>
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
                            <th>Especialista</th>
                            <th>Rut Paciente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $atencion->atencion }}</td>
                            <td>{{ date("d/m/Y", strtotime($atencion->fecha)) }}</td>
                            <td>{{ $atencion->especialidad }}</td>
                            <td>Dr/a. {{ $atencion->especialista->nombres }} {{ $atencion->especialista->apellido_paterno }}</td>
                            <td>{{ $atencion->rut_paciente }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row">

            <div class="col-6">
                <p class="lead">Métodos de Pago:</p>
                    <img src="{{ asset('img/mercado_pago.png') }}" alt="Mercado Pago" width="10%">
                </p>
            </div>

            <div class="col-6">
                <p class="lead">Importe Adeudado</p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:50%">Subtotal: {{ '$'.number_format($atencion->costo,0,'.','.') }}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Total: {{ '$'.number_format($atencion->costo,0,'.','.') }}</th>
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
                    <div class="cho-container float-left"></div>
                </div>
            </div>
        </div>
        <div></div>
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
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago("{{ config('services.mercado_pago.key') }}", {
        locale: 'es-CL'
    });

    mp.checkout({
        preference: {
            id: '{{ $preference->id }}'
        },
        render: {
            container: '.cho-container',
            label: 'Pagar Atención',
        }
    });
</script>
@stop