<?php

namespace App\Http\Controllers;

use App\Mail\ContactarEspecialista;
use App\Mail\ContactarPaciente;
use App\Models\Atencion;
use App\Models\AtencionZoom;
use App\Models\Bloques;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Horarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AtencionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
                $l_especialista = Especialistas::where('id_user',auth()->user()->id)->first();
                $atenciones = $objAtencion->getAtencionesByIdEspecialista($l_especialista->id);
                if(!empty($atenciones))
                {
                    foreach ($atenciones as $row)
                    {
                        $paciente = User::where('id',$row->id_paciente)->first();
                        $row->nombre_paciente = $paciente->name;
                         
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

                        $atenciones_zoom = AtencionZoom::where('id_atencion',$row->id_atencion)->first();

                        if(!empty($atenciones_zoom))
                        {
                            $row->atencion_zoom = 0;
                            $row->link_atencion = $atenciones_zoom->link_atencion;
                        }
                        else
                        {
                            $row->atencion_zoom = 1;
                        }
                        
                    }
                }
                $this->data['atenciones'] = $atenciones;
                return view('atencion.especialista.index',$this->data);
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

                        $atenciones_zoom = AtencionZoom::where('id_atencion',$row->id_atencion)->first();

                        if(!empty($atenciones_zoom))
                        {
                            $row->atencion_zoom = 0;
                            $row->link_atencion = $atenciones_zoom->link_atencion;
                        }
                        else
                        {
                            $row->atencion_zoom = 1;
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

            if(!empty($atenciones)) { $codigo = $atenciones->id_atencion + 1; } else { $codigo = 1; }

            if($request->input('tipo_atencion') == 1) { $fecha_save = $request->input("fecha_atencion"); } else { $fecha_save = null; }

            $fecha_hoy = date('Y-m-d');

            if(!is_null($fecha_save)) { if($fecha_save < $fecha_hoy) { $l_resp = 1; } else { $l_resp = 0; } } else { $l_resp = 0; }

            if($l_resp == 0)
            {
                $response = Atencion::create([
                    'codigo_atencion' =>  $codigo,
                    'tipo_atencion'   =>  $request->input('tipo_atencion'),
                    'id_especialidad' =>  $request->input('especialidad'),
                    'id_paciente'     =>  auth()->user()->id,
                    'rut_paciente'    =>  $request->input('rut'),
                    'detalle_atencion'=>  $request->input('detalle'),
                    'estado'          =>  4,
                    'fecha'           =>  $fecha_save
                ]);

                if ($response) 
                {
                    $this->data['status'] = "success";
                    $this->data['id']     = $response->codigo_atencion;
                }
                else 
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Hubo un error al crear la Atención, por favor intente nuevamente.";
                }
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "No puede ingresar una Fecha de Atención anterior al día actual.";
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
        $objBloque = new Bloques();

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
            $bloques2 = $objBloque->getHorarioByAtencion($horario->id_horario,$atentiones->fecha);
        }
        else
        {
            $bloques  = [];
            $bloques2 = [];
        }

        $this->data['atenciones']    = $atentiones;
        $this->data['especialistas'] = $especialistas;
        $this->data['especialidad']  = $especialidad;
        $this->data['tipo_atencion'] = $tipo_atencion;
        $this->data['bloques']       = $bloques;
        $this->data['bloques2']      = $bloques2;

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
        if(!empty($request->datos['tipo']))
        {
            switch($request->datos['tipo'])
            {
                case 0:
                    $this->data['status'] = "error";
                    $this->data['msg']    = "Faltan parámetros para realizar la Operación, intente nuevamente.";
                break;
                case 1:
                    if(!empty($request->datos['bloq']))
                    {
                        $partes       = explode('-',$request->datos['bloq']);
                        $bloque       = $partes[0];
                        $especialista = $partes[1];
                        $id_atencion  = $request->datos['id'];
                        
                        $id_horario = Bloques::where('id_bloque',$bloque)->first();

                        $response = Atencion::where('id_atencion',$id_atencion)->update([
                            'id_especialista'  =>  $especialista,
                            'id_horario'       =>  $id_horario->id_horario,
                            'id_bloque'        =>  $bloque,
                            'estado'          =>   3,
                        ]);

                        if ($response) 
                        {
                            $atencion = Atencion::where('id_atencion',$id_atencion)->first();
                            $this->data['status'] = "success";
                            $this->data['id']     = $atencion->codigo_atencion;
                        }
                        else 
                        {
                            $this->data['status'] = "error";
                            $this->data['msg'] = "Hubo un error al crear la Atención, por favor intente nuevamente.";
                        } 
                    }
                    else
                    {
                        $this->data['status'] = "error";
                        $this->data['msg'] = "Debe escoger un Horario con algún Especialista.";
                    }
                break;
                case 2:
                    if(!empty($request->datos['bloq']))
                    {
                        $especialista = $request->datos['bloq'];
                        $id_atencion  = $request->datos['id'];
                        
                        $response = Atencion::where('id_atencion',$id_atencion)->update([
                            'id_especialista'  =>  $especialista,
                            'estado'           =>   3,
                        ]);

                        if ($response) 
                        {
                            $atencion = Atencion::where('id_atencion',$id_atencion)->first();
                            $this->data['status'] = "success";
                            $this->data['id']     = $atencion->codigo_atencion;
                        }
                        else 
                        {
                            $this->data['status'] = "error";
                            $this->data['msg'] = "Hubo un error al crear la Atención, por favor intente nuevamente.";
                        } 
                    }
                    else
                    {
                        $this->data['status'] = "error";
                        $this->data['msg'] = "Debe escoger un Horario con algún Especialista.";
                    }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Request
     */
    public function contactarPaciente(Request $request)
    {
        if(!empty($request->input('id')))
        {
            $atencion     = Atencion::where('id_atencion',$request->input('id'))->first();
            $especialista = Especialistas::where('id',$atencion->id_especialista)->first();
            $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();

            $corr_pac = User::where('id',$atencion->id_paciente)->first();
            $corr_esp = User::where('id',$especialista->id_user)->first();

            $correo_paciente     = $corr_pac->email;
            $correo_especialista = $corr_esp->email;

            switch ($atencion->tipo_atencion) 
            { 
                case 1: $tipo_atencion = 'Atención Reservada'; $fecha = date("d/m/Y", strtotime($atencion->fecha)); break; 
                case 2: $tipo_atencion = 'Atención Inmediata'; $fecha = date("d/m/Y"); break;
            }

            $data = array(
                'codigo'        => $atencion->codigo_atencion,
                'rut'           => $atencion->rut_paciente,
                'paciente'      => $corr_pac->name,
                'especialidad'  => $especialidad->nombre,
                'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                'tipo_atencion' => $tipo_atencion,
                'fecha'         => $fecha
            );

            Mail::to($correo_paciente)->send(new ContactarPaciente($data));
            Mail::to($correo_especialista)->send(new ContactarEspecialista($data));

            $response = AtencionZoom::create([
                'id_atencion'     => $atencion->id_atencion,
                'codigo_atencion' => $atencion->codigo_atencion,
                'link_atencion'   => 'https://emeeds.cl',
            ]);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Enlace de atención enviado exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al enviar de atención, intente nuevamente.";
            }

        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg']    = "Faltan parámetros para realizar la acción, intente nuevamente.";
        }

        return json_encode($this->data);
    }
}
