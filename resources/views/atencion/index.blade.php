@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">CALENDARIO</h3>
        </div>
    </nav>
@stop

@section('sub_content')
<div class="card card-primary">
    <div class="card-body p-0">
        <div id="calendar"></div>
    </div>
</div>
@stop

@section('sub_js')
 
@stop