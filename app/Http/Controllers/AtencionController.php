<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Bloques;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AtencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objAtencion = new Atencion();
        switch (auth()->user()->tipo_user) 
        {
            //Especialista
            case 1:
            break;
            //Establecimiento
            case 2:
            break;
            //Paciente
            case 3:
                $atenciones = $objAtencion->getAtencionesByIdPaciente(auth()->user()->id);
                if(!empty($atenciones))
                {
                    foreach ($atenciones as $row)
                    {
                        $especialidad = Especialidades::where('id',$row->id_especialidad)->first();
                        $row->especialidad = $especialidad->nombre;

                        if(!empty($row->id_especialista))
                        {
                            $row->especialista = Especialistas::where('id',$row->id_especialista)->first();
                        }
                        else
                        {
                            $row->especialista = '';
                        }

                        switch ($row->tipo_atencion) 
                        {
                            case 1:
                                $row->atencion = 'Atención Reservada';
                            break;
                            case 2:
                                $row->atencion = 'Atención Inmediata';
                            break;
                            case 3:
                                $row->atencion = 'Sin tipo de atención';
                            break;
                        }
                        
                    }
                }
                $this->data['atenciones'] = $atenciones;
                return view('atencion.paciente.index',$this->data);
            break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $objEspecialidades = new Especialidades();

        $especialidades = $objEspecialidades->getEspecialidades();

        $this->data['especialidades'] = $especialidades;
    
        return view('atencion.paciente.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->input('tipo_atencion')))
        {
            if($request->input('tipo_atencion') == 1) { $fecha = 'required'; } else { $fecha = ''; }
        }
        else
        {
            $fecha = '';
        }

        $rules = array(
            'rut'           => 'required',
            'especialidad'  => 'required',
            'tipo_atencion' => 'required',
            'detalle'       => 'max:500',
            'fecha_atencion'=> $fecha,
        );

        $msg = array(
            'rut.required'            => 'El campo Rut es requerido',
            'especialidad.required'   => 'El campo Especialidad es requerido',
            'tipo_atencion.required'  => 'El campo Tipo de atención es requerido',
            'detalle.max'             => 'El campo Motivo de atención no puede superar los 500 caracteres',
            'fecha_atencion.required' => 'El campo Fecha de Atención es requerido'
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $atenciones = Atencion::where('id_paciente',auth()->user()->id)->orderBy('id_atencion','DESC')->first();

            if(!empty($atenciones))
            {
                $codigo = $atenciones->id_atencion + 1;
            }
            else
            {
                $codigo = 1;
            }

            if($request->input('tipo_atencion') == 1) 
            { 
                $fecha_save = $request->input("fecha_atencion"); 
            } 
            else 
            { 
                $fecha_save = null; 
            }

            $response = Atencion::create([
                'codigo_atencion' =>  $codigo,
                'tipo_atencion'   =>  $request->input('tipo_atencion'),
                'id_especialidad' =>  $request->input('especialidad'),
                'id_paciente'     =>  auth()->user()->id,
                'rut_paciente'    =>  $request->input('rut'),
                'detalle_atencion'=>  $request->input('detalle'),
                'estado'          =>  3,
                'fecha'           => $fecha_save
            ]);

            if ($response) 
            {
                $this->data['status'] = "success";
                $this->data['id']     = $response->codigo_atencion;
            }
            else 
            {
                $this->data['status'] = "error";
                $this->data['msg'] = $validador->errors()->first();
            } 
        }
        else 
        {
            $this->data['status'] = "error";
            $this->data['msg'] = $validador->errors()->first();
        } 

        return json_encode($this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $atentiones    = Atencion::where('codigo_atencion',$id)->first();
        $especialistas = Especialistas::where('id_especialidad',$atentiones->id_especialidad)->get();

        switch ($atentiones->tipo_atencion) 
        {
            case 1:
                $tipo_atencion = 'Atención Reservada';
                $day = date("l", strtotime($atentiones->fecha));
            break;
            case 2:
                $tipo_atencion = 'Atención Inmediata';
                $day = date("l");
            break;
            case 3:
                $tipo_atencion = 'Sin tipo de atención';
            break;
        }

        switch ($day) {
            case "Sunday":   $dia = 7;  break; case "Monday":    $dia = 1; break;
            case "Tuesday":  $dia = 2;  break; case "Wednesday": $dia = 3; break;
            case "Thursday": $dia = 4;  break; case "Friday":    $dia = 5; break;
            case "Saturday": $dia = 6;  break;
        }

        $especialidad  = Especialidades::where('id',$atentiones->id_especialidad)->first();
        $horario       = Horarios::where('dia',$dia)->first();

        if(!empty($horario))
        {
            $bloques = Bloques::where('id_horario',$horario->id_horario)->orderBy('hora_bloque','ASC')->get();
        }
        else
        {
            $bloques = [];
        }
        
        $this->data['atenciones']    = $atentiones;
        $this->data['especialistas'] = $especialistas;
        $this->data['especialidad']  = $especialidad;
        $this->data['tipo_atencion'] = $tipo_atencion;
        $this->data['bloques']       = $bloques;

        return view('atencion.paciente.create2',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!empty($request->input('tipo')))
        {
            switch($request->input('tipo'))
            {
                case 0:
                    $this->data['status'] = "error";
                    $this->data['msg']    = "Faltan parámetros para realizar la Operación, intente nuevamente.";
                break;
                case 1:
                    $rules = array(
                        'bloq' => 'required',
                    ); 
            
                    $msg = array(
                        'bloq.required' => 'Debe escoger un Horario',
                    );
            
                    $validador = Validator::make($request->all(), $rules, $msg);
            
                    if ($validador->passes()) 
                    {

                    }
                    else
                    {

                    }
                break;
                case 2:
                break;
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Faltan parámetros para realizar la Operación, intente nuevamente.";
        }
    
        return json_encode($this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->input('id'))
        {
            $response = Atencion::where('id_atencion',$request->input('id'))->delete();

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Atención Eliminada exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al eliminar la Atención, intente nuevamente.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Faltan parámetros para eliminar la Atención, intente nuevamente.";
        }

        return json_encode($this->data);
    }
}
