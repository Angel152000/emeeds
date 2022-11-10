@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">CALENDARIO</h3>
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
                        <li class="breadcrumb-item active">Eventos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('sub_content')
<div class="card card-primary">
  <div class="card-body p-3">
    <div id="calendar"></div>
  </div>
</div>
@stop

@section('sub_js')
<script>
    document.addEventListener('DOMContentLoaded', function() 
    {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        displayEventTime:false,
        headerToolbar: {
          left: 'prev next today',
          center: 'title',
          right: 'dayGridMonth timeGridWeek listWeek',
        }
      });
      calendar.render();
    });
</script>
@stop