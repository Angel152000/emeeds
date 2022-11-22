@extends('layouts.app')

@section('sub_content')
    @switch(auth()->user()->tipo_user)
        @case(1)
            <!-- Especialista --> 
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @break
        @case(2)
            <!-- Establecimiento --> 
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $countEspecialidad }}</h3>
                            <p>Especialidades</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-stethoscope"></i>
                        </div>
                        <a href="{{URL::route('especialidades')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $countEspecialistas }}</h3>
                            <p>Especialistas</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-duotone fa-user-nurse "></i>
                        </div>
                        <a href="{{URL::route('especialistas')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $countHorarios }}</h3>
                            <p>Horarios</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar "></i>
                        </div>
                        <a href="{{URL::route('horarios')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Atenciones Realizadas</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-laptop-medical "></i>
                        </div>
                        <a href="#" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end Establecimiento -->

            <!-- Tabla de especialidades --> 
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col text-left">
                                    <h3 class="card-title">Especialidades</h3>
                                </div>
                                <div class="col text-right">
                                    <a href="{{URL::route('especialidades')}}" class="btn btn-success btn-sm">
                                        <i class="fas fa-search"></i>
                                        Mantenedor de Especialidades
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="especialidades">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($especialidades))
                                        @foreach($especialidades as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ $row->codigo }}</td>
                                                <td>{{ $row->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <h4 class="text-center">No hay Especialidades aún.</h4>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Especialidades -->

            <!-- Tabla de especialistas --> 
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col text-left">
                                    <h3 class="card-title">Epecialistas</h3>
                                </div>
                                <div class="col text-right">
                                    <a href="{{URL::route('especialistas')}}" class="btn btn-success btn-sm">
                                        <i class="fas fa-search"></i>
                                        Mantenedor de Especialistas
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="especialistas">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Rut</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Especialidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($especialistas))
                                        @foreach($especialistas as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$row->rut}}</td>
                                                <td>{{$row->nombres}} {{$row->apellido_paterno}}</td>
                                                <td>{{$row->especialidad}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <h4 class="text-center">No hay Especialistas aún.</h4>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @break
        @case(3)
            <!-- Paciente --> 
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $countAtenciones }}</h3>
                            <p>Atenciones</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-laptop-medical"></i>
                        </div>
                        <a href="#" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Ficha</h3>
                            <p>Médica</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-hospital-user"></i>
                        </div>
                        <a href="#" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Configuración</h3>
                            <p>de la cuenta</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <a href="#" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Tabla de atenciones --> 
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col text-left">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#leyenda">
                                        <i class="fas fa-search"></i>
                                        Leyenda de Estados
                                    </button>
                                </div>
                                <div class="col text-right">
                                    <a href="{{URL::route('atenciones_pacientes')}}" class="btn btn-success btn-sm">
                                        <i class="fas fa-search"></i>
                                        Ver Atenciones
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
                                        <th scope="col">Tipo de atención</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Estado </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($atenciones))
                                        @foreach($atenciones as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ $row->codigo_atencion }}</td>
                                                <td>{{ $row->especialidad }}</td>
                                                @if(!empty($row->especialista))
                                                    <td>Dr/a. {{ $row->especialista->nombres }} {{ $row->especialista->apellido_paterno }}</td>
                                                @else
                                                    <td>No elegido.</td>
                                                @endif
                                                <td>{{ $row->atencion }}</td>
                                                @if(isset($row->fecha))
                                                    <td>@php echo date("d/m/Y", strtotime($row->fecha)); @endphp</td>
                                                @else
                                                    <td>No aplica.</td>
                                                @endif
                                                @switch($row->estado)
                                                    @case(1)
                                                        <td><h3><span class="badge badge-success">Realizada</span></h3></td>
                                                    @break
                                                    @case(2)
                                                        <td><h3><span class="badge badge-primary">En Proceso</span></h3></td>
                                                    @break
                                                    @case(3)
                                                        <td><h3><span class="badge badge-info">Pendiente Pago</span></h3></td>
                                                    @break
                                                    @case(4)
                                                        <td><h3><span class="badge badge-warning">Pendiente</span></h3></td>
                                                    @break
                                                @endswitch
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end atenciones -->

            <!-- Modal leyenda-->
            <div class="modal fade bd-leyenda-lg" id="leyenda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Leyenda de Estados</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <table class="table table-responsive">
                            <caption>Leyenda para los estados de la atención</caption>
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td><h3><span class="badge badge-success">Realizada</span></h3></td>
                                    <td>Consulta/Atención realizada con éxito.</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td><h3><span class="badge badge-primary">En Proceso</span></h3></td>
                                    <td>Consulta/Atención que se encuentra reservada o en proceso para ser atendido/a.</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td><h3><span class="badge badge-info">Pendiente Pago</span></h3></td>
                                    <td>Consulta/Atención que se encuentra completa para la reserva o atención inmediata en su defecto, pero que no se ha realizado el pago correspondiente.</td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td><h3><span class="badge badge-warning">Pendiente</span></h3></td>
                                    <td>Consulta/Atención que se encuentra incompleta para la reserva o atención inmediata de esta misma.</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @break
    @endswitch
    <br>
@stop

@section('sub_js')
    <script>
        $(document).ready(function() {
            var table = $('#especialistas').DataTable({
                responsive: true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ resultados por página",
                    "zeroRecords": "No Existen Especialistas aún.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                }
            });

            var table = $('#especialidades').DataTable({
                responsive: true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ resultados por página",
                    "zeroRecords": "No Existen Especialistas aún.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                }
            });

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

        $('.cerrar_ses').click(function(){
            document.getElementById('logout-form').submit();
        });
    </script>
@stop