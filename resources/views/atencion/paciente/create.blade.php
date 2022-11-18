@extends('layouts.app')

@section('sub_header')
    <nav style="background: linear-gradient(90deg, #019df4, #f4f6f9);padding: 1rem;" class="navbar navbar-danger">
        <div clas="container">
            <h3 class="text-light font-weight-bold">ATENCIONES</h3>
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
                        <li class="breadcrumb-item"><a href="{{URL::route('atenciones_pacientes')}}">Atenciones</a></li>
                        <li class="breadcrumb-item active">Reservar Atención</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('sub_content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Reserva o Realiza de forma inmediata tu Atención.</h3>
            </div>
            <form method="POST" id="form" action="{{URL::route('atenciones_crear')}}" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group">
                        <label>Rut <span style="color:red;">*</span></label>
                        <input type="text" id="rut" name="rut" class="form-control for-especialista" placeholder="Ingrese solo números" onkeypress="return check(event)">
                    </div>
                    <div class="form-group">
                        <label>Especialidad <span style="color:red;">*</span></label>
                        <select class="custom-select especialidades for-especialista" id="especialidad" name="especialidad">
                            <option selected>Ingresa la Especialidad</option>
                            @if(!empty($especialidades))
                                @foreach($especialidades as $row)
                                    <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                @endforeach
                            @else
                                <option>No existen Especialidades Creadas.</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Seleccione el Tipo de Atención. <span style="color:red;">*</span></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_atencion" id="flexRadioDefault1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Reserva de Atención
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_atencion" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Atención Inmediata
                        </label>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Siguiente <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('sub_js')
 <script>
    $('.especialidades').select2();

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
                            window.location.reload();
                        }
                        else
                        {
                            window.location.reload();
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
 </script>
@stop