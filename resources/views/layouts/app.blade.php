@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('title', 'Dashboard')

@section('content_header')
    @yield("sub_header")
@stop

@section('content')
    @yield("sub_content")
@stop

@section('css')
    <script src="https://kit.fontawesome.com/97f87ec59b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield("sub_js")
@stop