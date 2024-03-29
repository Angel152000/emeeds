@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('title', 'Dashboard')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield("sub_header")
@stop

@section('content')
    @yield("sub_content")
@stop

@section('footer')
<div class="float-right d-none d-sm-block">
    <b>Version</b> 3.0
</div>
<strong>Software Potenciado por <a href="https://www.emeeds.cl">Emeeds</a>.</strong> Copyright © 2022 - Todos los derechos son Reservados.
@stop

@section('css')
    <script src="https://kit.fontawesome.com/97f87ec59b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{ asset('vendor/fullcalendar/main.css') }}" rel='stylesheet' />
@stop

@section('js')
    <script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/locales/es.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.cerrar_ses').click(function() {
            document.getElementById('logout-form').submit();
        });
    </script>
    @yield("sub_js")
@stop