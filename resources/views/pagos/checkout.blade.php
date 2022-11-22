@extends('layouts.app')

<?php
    // SDK de Mercado Pago
    require base_path('vendor/autoload.php');

    // Agrega credenciales
    MercadoPago\SDK::setAccessToken(config('services.mercado_pago.token'));

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();

    //Urls direccionamiento.
    $preference->back_urls = array(
        "" =>
        "" =>
        "" =>
    );

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = 'Atención Medica';
    $item->quantity = 1;
    $item->unit_price = 75;
    $preference->items = array($item);
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
    <div class="cho-container"></div>
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
            label: 'Pagar',
            }
        });
    </script>
@stop