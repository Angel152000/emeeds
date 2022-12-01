<?php

namespace App\Http\Controllers;

use App\Mail\CancelarAtencion;
use App\Mail\ContactarEspecialista;
use App\Mail\ContactarPaciente;
use App\Models\Atencion;
use App\Models\AtencionZoom;
use App\Models\Bloques;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Fichas;
use App\Models\Horarios;
use App\Models\Pagos;
use App\Models\User;
use App\Models\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

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

                        $ficha = Fichas::where('id_atencion',$row->id_atencion)->first();
    
                        if(!empty($ficha))
                        {
                            $row->notas = $ficha->nota;
                        }
                        else
                        {
                            $row->notas = '';
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

                        $bloque = Bloques::where('id_bloque',$row->id_bloque)->where('id_horario',$row->id_bloque)->first();
                        if(!empty($bloque))
                        {
                            $row->nom_bloq = $bloque->hora_bloque;
                        }
                        else
                        {
                            $row->nom_bloq = 'No aplica.';
                        }
                        
                    }
                }
                $this->data['atenciones'] = $atenciones;
                return view('atencion.especialista.index',$this->data);
            break;
            //Establecimiento
            case 2:
                $atenciones = $objAtencion->getAtencionesByAdmin(auth()->user()->id);
                if(!empty($atenciones))
                {
                    foreach ($atenciones as $row)
                    {
                        $especialidad = Especialidades::where('id',$row->id_especialidad)->first();
                        $row->especialidad = $especialidad->nombre;

                        $paciente = User::where('id',$row->id_paciente)->first();
                        $row->nombre_paciente = $paciente->name;

                        $monto = Pagos::where('id_atencion',$row->id_atencion)->first();
                        $row->monto_pago = $monto->monto_pago;

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
                return view('atencion.establecimiento.index',$this->data);
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
        $especialistas = Especialistas::where('id_especialidad',$atentiones->id_especialidad)->where('tipo_atencion',$atentiones->tipo_atencion)->get();
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

                        if($id_horario->hora_bloque < date('H:i:s'))
                        {
                            $this->data['status'] = "error";
                            $this->data['msg'] = "No puede ingresar en una hora posterior a reservar el horario de atención elegido.";
                        }
                        else
                        {
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
     * Cambiar estado de atencion.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstado(Request $request)
    {
        if($request->input('id'))
        {
            $response =  Atencion::where('id_atencion',$request->input('id'))->update([
                'estado' => 1,
            ]);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Estado de la atención actualizado exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al actualizar el estado de la tención, intente nuevamente.";
            }

        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "No puede cambiar el estado a realizado, debido a que no ha iniciado la reunión.";
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
            $zoom_account = Zoom::where('id_user',auth()->user()->id)->first();
            if(!empty($zoom_account))
            {
                $reautorizar = $this->reautorizar();
            
                if($reautorizar == 'CORRECTO')
                {
                    $atencion     = Atencion::where('id_atencion',$request->input('id'))->first();
                    $especialista = Especialistas::where('id',$atencion->id_especialista)->first();
                    $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();

                    $zoom_account = Zoom::where('id_user',auth()->user()->id)->first();
                    $zoom = $this->create_meeting($zoom_account->id_zoom,$zoom_account->access_token,$especialista->nombres.' '.$especialista->apellido_paterno);
                    
                    if(!empty($zoom))
                    {

                        $corr_pac = User::where('id',$atencion->id_paciente)->first();
                        $corr_esp = User::where('id',$especialista->id_user)->first();

                        $correo_paciente     = $corr_pac->email;
                        $correo_especialista = $corr_esp->email;

                        switch ($atencion->tipo_atencion) 
                        { 
                            case 1: $tipo_atencion = 'Atención Reservada'; $fecha = date("d/m/Y", strtotime($atencion->fecha)); break; 
                            case 2: $tipo_atencion = 'Atención Inmediata'; $fecha = date("d/m/Y"); break;
                        }

                        $partes = explode("?",$zoom->join_url);

                        $data = array(
                            'codigo'        => $atencion->codigo_atencion,
                            'rut'           => $atencion->rut_paciente,
                            'paciente'      => $corr_pac->name,
                            'especialidad'  => $especialidad->nombre,
                            'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                            'tipo_atencion' => $tipo_atencion,
                            'fecha'         => $fecha,
                            'link'          => $partes[0],
                            'password'      => $zoom->password
                        );

                        $response = AtencionZoom::create([
                            'id_atencion'     => $atencion->id_atencion,
                            'codigo_atencion' => $atencion->codigo_atencion,
                            'id_reunion_zoom' => $zoom->id,
                            'password_zoom'   => $zoom->password,
                            'link_atencion'   => $partes[0],
                        ]);

                        if($response)
                        {
                            Mail::to($correo_paciente)->send(new ContactarPaciente($data));
                            Mail::to($correo_especialista)->send(new ContactarEspecialista($data));


                            if($atencion->tipo_atencion == 1)
                            {
                                $fecha = $atencion->fecha;
                            }
                            else
                            {
                                $fecha = date('Y-m-d');
                            }

                            $ficha = Fichas::create([
                                'id_atencion'     =>  $atencion->id_atencion,
                                'id_paciente'     =>  $atencion->id_paciente,
                                'rut_paciente'    =>  $atencion->rut_paciente,
                                'id_especialista' =>  $atencion->id_especialista,
                                'id_especialidad' =>  $atencion->id_especialidad,
                                'fecha_atencion'  =>  $fecha,
                            ]);
                            
                            if($ficha)
                            {
                                $this->data['status'] = "success";
                                $this->data['msg'] = "Enlace de atención enviado exitosamente.";
                            }
                            else
                            {
                                $this->data['status'] = "error";
                                $this->data['msg'] = "Se envío correctamente la notificación, pero no se pudo crear la ficha médica del paciente, contacte a soporte (err-F001).";
                            }
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
                        $this->data['msg'] = "Hubo un error al crear la reunión de Zoom (err-Z001), intente nuevamente, si el error persiste contacte a soporte.";
                    }
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = $reautorizar;
                }
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "No tiene cuenta de zoom vinculada, por favor cree una en la ubicación cuenta > zoom.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg']    = "Faltan parámetros para realizar la acción, intente nuevamente.";
        }

        return json_encode($this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Request
     */
    public function reenviarPaciente(Request $request)
    {
        if(!empty($request->input('id')))
        {
            $zoom_account = Zoom::where('id_user',auth()->user()->id)->first();
            if(!empty($zoom_account))
            {
                $reautorizar = $this->reautorizar();

                if($reautorizar == 'CORRECTO')
                {
                    $atencion     = Atencion::where('id_atencion',$request->input('id'))->first();
                    $especialista = Especialistas::where('id',$atencion->id_especialista)->first();
                    $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();

                    $zoom_account = Zoom::where('id_user',auth()->user()->id)->first();
                    $zoom = $this->create_meeting($zoom_account->id_zoom,$zoom_account->access_token,$especialista->nombres.' '.$especialista->apellido_paterno);
                    
                    if(!empty($zoom))
                    {
                        $corr_pac = User::where('id',$atencion->id_paciente)->first();
                        $corr_esp = User::where('id',$especialista->id_user)->first();

                        $correo_paciente     = $corr_pac->email;
                        $correo_especialista = $corr_esp->email;

                        switch ($atencion->tipo_atencion) 
                        { 
                            case 1: $tipo_atencion = 'Atención Reservada'; $fecha = date("d/m/Y", strtotime($atencion->fecha)); break; 
                            case 2: $tipo_atencion = 'Atención Inmediata'; $fecha = date("d/m/Y"); break;
                        }

                        $partes = explode("?",$zoom->join_url);

                        $data = array(
                            'codigo'        => $atencion->codigo_atencion,
                            'rut'           => $atencion->rut_paciente,
                            'paciente'      => $corr_pac->name,
                            'especialidad'  => $especialidad->nombre,
                            'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                            'tipo_atencion' => $tipo_atencion,
                            'fecha'         => $fecha,
                            'link'          => $partes[0],
                            'password'      => $zoom->password
                        );

                        $response = AtencionZoom::where('id_atencion',$request->input('id'))->update([
                            'id_reunion_zoom' => $zoom->id,
                            'password_zoom'   => $zoom->password,
                            'link_atencion'   => $partes[0]
                        ]);

                        if($response)
                        {
                            Mail::to($correo_paciente)->send(new ContactarPaciente($data));
                            Mail::to($correo_especialista)->send(new ContactarEspecialista($data));

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
                        $this->data['msg'] = "Hubo un error al crear la reunión de Zoom (err-Z001), intente nuevamente, si el error persiste contacte a soporte.";
                    }
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = $reautorizar;
                }
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "No tiene cuenta de zoom vinculada, por favor cree una en la ubicación cuenta > zoom.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg']    = "Faltan parámetros para realizar la acción, intente nuevamente.";
        }

        return json_encode($this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Request
     */
    public function cancelarAtencion(Request $request)
    {
        if(!empty($request->input('id')))
        {
            $response = Atencion::where('id_atencion',$request->input('id'))->update([
                'estado' => '5'
            ]);

            if($response)
            {
                $atencion     = Atencion::where('id_atencion',$request->input('id'))->first();
                $pago         = Pagos::where('id_atencion',$request->input('id'))->first();
                $paciente     = User::where('id',$atencion->id_paciente)->first();
                $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();
                $especialista = Especialistas::where('id',$atencion->id_especialista)->first();

                if($atencion->tipo_atencion == 1){ $tipo_atencion = 'Atención Reservada'; } else { $tipo_atencion = 'Atención Inmediata'; } 

                $data = array(
                    'codigo'        => $atencion->codigo_atencion,
                    'paciente'      => $paciente->name,
                    'especialidad'  => $especialidad->nombre,
                    'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                    'tipo_atencion' => $tipo_atencion,
                    'fecha'         => date('d-m-Y'),
                    'id_pago'       => $pago->payment_id,
                    'id_orden'      => $pago->merchant_order_id,
                    'monto'         => '$'.number_format($pago->monto_pago,0,'.','.'),
                );

                Mail::to($paciente->email)->send(new CancelarAtencion($data));

                $this->data['status'] = "success";
                $this->data['msg'] = "Se ha cancelado la atención exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al cancelar la atención, intente nuevamente.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg']    = "Faltan parámetros para realizar la acción, intente nuevamente.";
        }
        return json_encode($this->data);
    }

    /*
    * Api que crea una reunion en zoom por el id del usuario de zoom.
    */
    protected function create_meeting($id,$token,$nombre_especialista) 
    {
        $api_url = "https://api.zoom.us/v2/users/".$id."/meetings";

        $password = $this->generate_password(8);

        $post_data = array(
            "topic"      => "Atención Medica, Dr/a ".$nombre_especialista,
            "type"       => 2,
            "duration"   => "45",
            "password"   => $password,
            "enforce_login_domains" => "meeting_authentication"
        );

        $type = 'post';

        $acces_token = 'Authorization: Bearer '.$token;

        $result = $this->_conecttionM($api_url, $post_data, $type, $acces_token);

        return json_decode($result);

    }

    protected function generate_password($length)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern)-1;
        for($i = 0; $i < $length; $i++){
            $key .= substr($pattern, mt_rand(0,$max), 1);
        }
        return $key;
    }

    /**
     * funcion que realiza un refresh del token de la cuenta del usuario en zoom y nuestro sistema.
     *
     * @return \Illuminate\Http\Response
    */
    public function reautorizar()
    {
        $objZomm = new Zoom();

        $getEstadoCuenta = $objZomm->getUser(auth()->user()->id);
        
        if($getEstadoCuenta)
        {
            $user = $this->reautorizar_cuenta($getEstadoCuenta->refresh_token);

            if($user->access_token)
            {
                $date = Carbon::now();

                $horas = floor($user->expires_in / 3600);
                $minutos = floor(($user->expires_in - ($horas * 3600)) / 60);
                $segundos = $user->expires_in - ($horas * 3600) - ($minutos * 60);

                $expira_en = $horas . ':' . $minutos . ":" . $segundos;

                $data = array(
                    'access_token'  => $user->access_token,
                    'token_type'    => $user->token_type,
                    'refresh_token' => $user->refresh_token,
                    'expires_in'    => $expira_en,
                    'scope'         => $user->scope,
                    'estado'        => 'activado',
                    'updated_at'    => $date,
                );

                $update = $objZomm->updateUserByZoom(auth()->user()->id,$data);

                if($update)
                {
                    $cod = 'CORRECTO';
                }
                else
                {
                    $cod = 'No se pudo reautorizar la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:003).';
                }
            }
            else
            {
                $cod = 'No se pudo reautorizar la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:002).';
            }
        }
        else
        {
            $cod = 'No se encuenta la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:001).';
        }

        return $cod;
    }

    /*
    * Api que desvincula la cuenta del usuario que dio permisos a la app de zoom.
    */
    protected function reautorizar_cuenta($refresh_token)
    {
        $api_url = "https://zoom.us/oauth/token";

        $post_data = array(
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token
        );

        $type = 'post';

        $code_base64 = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
        $acces_token = 'Authorization: Basic '.$code_base64;

        $result = $this->_conecttionB($api_url, $post_data, $type, $acces_token);

        return json_decode($result);
    }

    private function _conecttionM($api_url, $post_data, $type, $acces_token)
    {
        $headers = [
            $acces_token,
            'Content-Type: application/json',
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, $api_url);

        switch ($type) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
            break;
            case 'get':
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            break;
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
            break;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        // Comprobar si occurió algún error
        if (curl_errno($curl)) {
            dump(curl_error($curl));
        }
    
        curl_close($curl);

        return $result;
    }

    private function _conecttionB($api_url, $post_data, $type, $acces_token)
    {
        $headers = [
            $acces_token,
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, $api_url);

        switch ($type) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
            break;
            case 'get':
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            break;
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            break;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        // Comprobar si occurió algún error
        if (curl_errno($curl)) {
            dump(curl_error($curl));
        }
    
        curl_close($curl);

        return $result;
    }
}
