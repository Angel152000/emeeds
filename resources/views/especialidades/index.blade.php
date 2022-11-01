@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">ESPECIALIDADES</h3>
        </div>
    </nav>
@stop

@section('sub_content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col text-left">
                <h3 class="card-title">Mantenedor de Especialidades impartidas en el establecimiento</h3>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa-solid fa-circle-plus"></i>
                    Agregar Especialidades
                </button>
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
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($especialidades))
                    @foreach($especialidades as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->codigo }}</td>
                            <td>{{ $row->nombre }}</td>
                            <td>{{ $row->descripcion }}</td>
                            <td>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editarEspecialidad">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <a onclick="editarEspecialidad('{{$row->id}}')"   class="btn btn-success" rel-id="" href="#" title="Editar Servicio"><i class="fa-solid fa-pencil"></i></i></a>
                                <a onclick="eliminarEspecialidad('{{$row->id}}')" class="btn btn-danger"  rel-id="" href="#" title="Eliminar Servicio"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <!-- Modal Edit-->
                        <div class="modal fade" id="editarEspecialidad" tabindex="-1" aria-labelledby="editarEspecialidad" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar Especialidades</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="codigo" class="col-form-label">Código <span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" id="codigo_{{$row->id}}" name="codigo" value="{{ $row->codigo }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="nombre" class="col-form-label">Nombre <span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" id="nombre_{{$row->id}}" name="nombre" value="{{ $row->nombre }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion" class="col-form-label">Descripción <span style="color:red;">*</span></label>
                                            <textarea class="form-control" name="descripcion" id="descripcion">{{ $row->descripcion }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <h4 class="text-center">No hay categorías aún, Porfavor Cree una.</h4>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Create-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Especialidades</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form" action="{{URL::route('crear_especialidades')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigo" class="col-form-label">Código <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="codigo" name="codigo">
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-form-label">Nombre <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="col-form-label">Descripción <span style="color:red;">*</span></label>
                        <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('sub_js')
<script>
    $('#especialidades').DataTable( {
        responsive: true
    });

    $("#form").submit(function(e)
    {
        e.preventDefault();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (res) 
            {
                if (res.status === 'success') 
                {
                    Swal.fire({
                        title: res.msg,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#019df4',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    });  
</script>
@stop