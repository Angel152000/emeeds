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
            <div class="card-body">
                <div class="form-group">
                    <label>Rut <span style="color:red;">*</span></label>
                    <input type="text" id="rut" name="rut" class="form-control for-especialista" placeholder="Ingrese solo números" onkeypress="return check(event)">
                </div>
                <div class="form-group">
                    <label>Especialidad <span style="color:red;">*</span></label>
                    <select class="custom-select especialidades for-especialista col" id="especialidad" name="especialidad">
                        <option selected value="">Ingresa la Especialidad</option>
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
                    <label>Seleccione el Tipo de Atención <span style="color:red;">*</span></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_atencion" id="tipo_atencion" checked value="1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Reserva de Atención
                    </label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_atencion" id="tipo_atencion" value="2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Atención Inmediata
                    </label>
                </div>
                <br>
                <div class="form-group fecha_aten">
                    <label>Fecha de Atención <span style="color:red;">*</span></label>
                    <input class="form-control" type="date" name="fecha_atencion" id="fecha_atencion" min=<?php $hoy=date("Y-m-d"); echo $hoy;?> >
                </div>
                <br>
                <div class="form-group">
                    <label>Motivo de Atención.</label>
                    <textarea class="form-control" id="detalle" name="detalle" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{URL::route('atenciones_pacientes')}}" class="btn btn-danger">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver
                </a>
                <button class="btn btn-success" onclick="crearAtencion()">Siguiente <i class="fa-solid fa-arrow-right"></i></button>
            </div>
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

    function crearAtencion() 
    {
        var url = '{{URL::route("atenciones_crear")}}';

        $.ajax({
            url: url,
            type: 'post',
            dataType : 'json',
            data: {
                rut:           $("#rut").val(),
                especialidad:  $("#especialidad").val(),
                tipo_atencion: $('input[name="tipo_atencion"]:checked').val(),
                detalle:       $("#detalle").val(),
                fecha_atencion:$("#fecha_atencion").val(),
            },
            success: function(res) 
            {
                if (res.status === 'success') 
                {
                   window.location.href = "{{ url('/home/atenciones/paciente/reservar/especialista/') }}/"+res.id;
                }
                else
                {
                    Swal.fire('Error!',res.msg,'error');
                }  
            }
        });
    }

    $('input[type=radio][name=tipo_atencion]').change(function() 
    {
        if (this.value == '1') 
        {
            $('.fecha_aten').show();
        }
        else if (this.value == '2') 
        {
            $('.fecha_aten').hide();
        }
    });
 </script>
@stop