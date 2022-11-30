@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">FICHA PACIENTE ({{ $paciente }})</h3>
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
                        <li class="breadcrumb-item"><a href="{{URL::route('pacientes')}}">Pacientes Atendidos</a></li>
                        <li class="breadcrumb-item active">Ficha Paciente</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('sub_content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col text-left">
                        <h3 class="card-title">Mantenedor de Pacientes Atendidos.</h3>
                    </div>
                    <div class="col text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="pacientes" style="width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tipo de Atención</th>
                            <th scope="col">Fecha de Atención</th>
                            <th scope="col">Especialista</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($atenciones))
                            @foreach($atenciones as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->atencion }}</td>
                                    <td>{{ $row->fecha_at }}</td>
                                    <td>{{ $row->especialista }}</td>
                                    <td>{{ $row->especialidad }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#verNotas_{{$row->id_atencion}}">
                                            <i class="fa-solid fa-notes-medical"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal Edit-->
                                <div class="modal fade" id="verNotas_{{$row->id_atencion}}" tabindex="-1" aria-labelledby="editarEspecialidad" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Notas de Atención</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if($row->id_especialista == $id_especialista)
                                                    <div class="form-group">
                                                        <label for="descripcion" class="col-form-label">Notas <span style="color:red;">*</span></label>
                                                        <textarea class="form-control" name="descripcion" id="descripcion_{{$row->id_atencion}}">{{ $row->notas }}</textarea>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label for="descripcion" class="col-form-label">Notas <span style="color:red;">*</span></label>
                                                        <p>{{ $row->notas }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                @if($row->id_especialista == $id_especialista)
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                                                    <button type="button" onclick="guardarNota('{{ $row->id_atencion }}')" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                                @else
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
    $(document).ready(function() {
        var table = $('#pacientes').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay atenciones hechas por el paciente.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });

    function guardarNota(id) 
    {
        var url = '{{URL::route("editar_nota")}}';
        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: {
                id:          id,
                nota: $("#descripcion_"+id).val()
            },
            success: function(res) 
            {
                if (res.status === 'success') 
                {
                    Swal.fire({
                        title: res.msg,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#019df4',
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#dc3545',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) 
                        {
                            window.location.reload();
                        }
                        else
                        {
                            window.location.reload();
                        }
                    })
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    }
</script>
@stop