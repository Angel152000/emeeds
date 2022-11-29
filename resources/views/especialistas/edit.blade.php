@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">EDITAR ESPECIALISTA</h3>
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
                        <li class="breadcrumb-item"><a href="{{URL::route('especialistas')}}">Especialistas</a></li>
                        <li class="breadcrumb-item active">Editar Especialista</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('sub_content')
<div class="row">
    <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" method="POST" id="form" action="{{URL::route('editar_especialistas')}}" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $especialista->id }}">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-default">
                <div class="card-header" data-card-widget="collapse">
                    <h3 class="card-title">Datos del Especialista</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Rut <span style="color:red;">*</span></label>
                                <input type="text" id="rut" name="rut" class="form-control for-especialista" value="{{ $especialista->rut }}" placeholder="Ingrese solo números" onkeypress="return check(event)">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Nombres <span style="color:red;">*</span></label>
                                <input type="text" id="nombres" name="nombres" class="form-control for-especialista" value="{{ $especialista->nombres }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Apellido Paterno <span style="color:red;">*</span></label>
                                <input type="text" id="apellidop" name="apellidop" class="form-control for-especialista" value="{{ $especialista->apellido_paterno }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Apellido Materno <span style="color:red;">*</span></label>
                                <input type="text" id="apellidom" name="apellidom" class="form-control for-especialista" value="{{ $especialista->apellido_materno }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Fecha de Nacimiento <span style="color:red;">*</span></label>
                                <input type="date" id="fecha_nac" name="fecha_nac" class="form-control for-especialista" value="{{ $especialista->fecha_nacimiento }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Sexo <span style="color:red;">*</span></label>
                                <select class="custom-select for-especialista" id="sexo" name="sexo">
                                    <option selected value="">- Selecciona -</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                    <option value="3">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Email <span style="color:red;">*</span></label>
                                <input type="email" id="email" name="email" class="form-control for-especialista" placeholder="example@gmail.com" value="{{ $especialista->email }}" disabled>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Teléfono <span style="color:red;">*</span></label>
                                <input type="text" id="telefono" name="telefono" class="form-control for-especialista" placeholder="999999999" onkeypress="return check(event)" value="{{ $especialista->telefono }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-default">
                <div class="card-header"  data-card-widget="collapse">
                    <h3 class="card-title">Atención</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Seleccione el tipo de Atención. <span style="color:red;">*</span></label>
                                <select class="form-select form-control" id="tipo_atencion" name="tipo_atencion">
                                    <option selected value="">- Selecciona -</option>
                                    <option value="1">Atención con reserva</option>
                                    <option value="2">Atención Inmediata</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-default">
                <div class="card-header"  data-card-widget="collapse">
                    <h3 class="card-title">Especialidad</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Ingrese la especialidad <span style="color:red;">*</span></label>
                                <select class="custom-select especialidades for-especialista" id="especialidad" name="especialidad">
                                    <option selected>- Selecciona -</option>
                                    @if(!empty($especialidades))
                                        @foreach($especialidades as $row)
                                            <option value="{{ $row->id }}">{{ $row->codigo }} {{ $row->nombre }}</option>
                                        @endforeach
                                    @else
                                        <option>No existen Especialidades Creadas.</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            <a href="{{URL::route('especialistas')}}" class="btn btn-danger">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
        <br>
    </form>
    <input type="hidden" id="sexoval" value="{{ $especialista->sexo }}">
    <input type="hidden" id="espeval" value="{{ $especialista->id_especialidad }}">
    <input type="hidden" id="tipval"  value="{{ $especialista->tipo_atencion }}">
</div>
@stop

@section('sub_js')
<script>
    $('.especialidades').select2();

    var sexo = $("#sexoval").val();
    $("#sexo option[value="+sexo+"]").attr("selected",true);

    var espe = $("#espeval").val();
    $('#especialidad').val(espe).trigger('change.select2');

    var tipo = $("#tipval").val();
    $("#tipo_atencion option[value="+tipo+"]").attr("selected",true);

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
                            window.location.href = "{{URL::route('especialistas')}}";
                        }
                        else
                        {
                            window.location.href = "{{URL::route('especialistas')}}";
                        }
                    })

                    $(".for-especialista").val('');
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }
            }
        });
    });

    function check(e) 
    {
		tecla = (document.all) ? e.keyCode : e.which;

		//Tecla de retroceso para borrar, siempre la permite
		if (tecla == 8) {
			return true;
		}

		// Patron de entrada, en este caso solo acepta numeros y letras
		patron = /[0-9]/;

		tecla_final = String.fromCharCode(tecla);
		return patron.test(tecla_final);
	}

    function formatNumber (n) 
	{
		n = String(n).replace(/\D/g, "");
		return n === '' ? n : Number(n).toLocaleString('de-DE');
	}

    $("#rut").keyup(function(e)
	{
		const element = e.target;
		const value = element.value;
		var dv = value.charAt(value.length-1);
		var onlyRut = formatNumber(value.slice(0,-1));

		if(value.length == 10 && dv != '-')
		{
			element.value = onlyRut + '-' + dv; 
		}
		else if(value.length == 11  && dv != '-')
		{
			element.value = onlyRut + '-' + dv; 
		}
		else if(value.length == 12  && dv != '-')
		{
			element.value = onlyRut + '-' + dv; 
		}
		else
		{
			if(value.length == 11)
			{
				var newValCompleto = value.slice(0,-1); 
				var newDv = newValCompleto.charAt(newValCompleto.length-1);
				var newOnlyRut = formatNumber(newValCompleto.slice(0,-1));
				element.value = newOnlyRut + '-' + newDv; 
			}
			else
			{
				value.replace = ('-', '');
				element.value = formatNumber(value);
			}
		}

	});
</script>
@stop