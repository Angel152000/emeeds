<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Especialidades;
use App\Models\Especialistas;
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
        $rules = array(
            'rut'           => 'required',
            'especialidad'  => 'required',
            'tipo_atencion' => 'required',
        ); 

        $msg = array(
            'rut.required'            => 'El campo Rut es requerido',
            'especialidad.required'   => 'El campo Especialidad es requerido',
            'tipo_atencion.required'  => 'El campo Tipo de atención es requerido',
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

            $response = Atencion::create([
                'codigo_atencion' =>  $codigo,
                'tipo_atencion'   =>  $request->input('tipo_atencion'),
                'id_especialidad' =>  $request->input('especialidad'),
                'id_paciente'     =>  auth()->user()->id,
                'rut_paciente'    =>  $request->input('rut'),
                'estado'          =>  3
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
        return view('atencion.paciente.create2');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function edit(atencion $atencion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, atencion $atencion)
    {
        //
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
