@extends('adminlte::page')

@section('title', 'Dashboard | Emeeds')

@section('content_header')
@stop

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content')
    @yield('content')
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    @yield('js')
@stop