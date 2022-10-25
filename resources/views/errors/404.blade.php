@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('title', 'Dashboard')

@section('content_header')
    @yield("sub_header")
@stop

@section('content')
<div class="content-wrapper" style="min-height: 1604.8px;">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{URL::route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">404 Error Page</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="error-page">
        <h2 class="headline text-success"><b>404</b></h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Página no Encontrada.</h3>
            <p>
                No pudimos encontrar la página que estabas buscando.  Mientras tanto, puede volver al <a href="{{URL::route('home')}}">dashboard</a>.
            </p>
        </div>
    </div>
</section>

</div>
@stop

@section('css')
    <script src="https://kit.fontawesome.com/97f87ec59b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield("sub_js")
@stop