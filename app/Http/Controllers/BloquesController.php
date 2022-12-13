<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Bloques;
use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BloquesController extends Controller
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
    public function index($id)
    {
        $horarios = Horarios::where('id_horario',$id)->first();
        $bloques  = Bloques::where('id_horario',$id)->orderBy('hora_bloque','ASC')->get();

        $this->data['horarios'] = $horarios;
        $this->data['bloques'] = $bloques;

        return view('bloques.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = array(
            'numero_bloque'  => 'required|numeric',
            'hora'           => 'required',
            'bloque'         => 'required|max:500',
        ); 

        $msg = array(
            'numero_bloque.required'  => 'El campo Número de Bloque es requerido.',
            'numero_bloque.numeric'   => 'El campo Número de Bloque solo acepta valores numéricos.',
            'hora.required'           => 'El campo Hora es requerido.',
            'bloque.required'         => 'El campo Descripción de Bloque es requerido.',
            'bloque.max'              => 'El campo Descripción de Bloque no puede superar los 500 caracteres.',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $bloq = Bloques::where([
                ['id_horario', '=', $request->input('id_h')],
                ['hora_bloque','=', $request->input('hora').':00']
            ])->get();
            
            if(!empty($bloq))
            {
                $response = Bloques::create([
                    'id_horario'    =>  $request->input('id_h'),
                    'numero_bloque' =>  $request->input('numero_bloque'),
                    'hora_bloque'   =>  $request->input('hora'),
                    'bloque_desc'   =>  $request->input('bloque'),
                ]);

                if($response)
                {
                    $this->data['status'] = "success";
                    $this->data['msg'] = "Bloque Creado exitosamente.";
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Hubo un error al Crear El Bloque, intente nuevamente.";
                }
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "La hora del Bloque ya existe, ingrese uno nuevo o edite el existente.";
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bloques  $bloques
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'hora'           => 'required',
            'bloque'         => 'required|max:500',
        ); 

        $msg = array(
            'hora.required'           => 'El campo Hora es requerido.',
            'bloque.required'         => 'El campo Descripción de Bloque es requerido.',
            'bloque.max'              => 'El campo Descripción de Bloque no puede superar los 500 caracteres.',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $bloques = Bloques::where([
                ['id_horario', '=', $request->input('id_h')],
                ['hora_bloque','=', $request->input('hora').':00']
            ])->first();

            if(!empty($bloques)) { $bloq = $bloques->id_bloque; } else { $bloq = ''; } 

            $bloq2 = Bloques::where('id_bloque',$request->input('id'))->first();
            
            if(!empty($bloq2))
            {
                if($bloq == $bloq2->id_bloque)
                {
                    $response = Bloques::where('id_bloque',$request->input('id'))->update([
                        'hora_bloque'   =>  $request->input('hora'),
                        'bloque_desc'   =>  $request->input('bloque'),
                    ]);
        
                    if($response)
                    {
                        $this->data['status'] = "success";
                        $this->data['msg'] = "Bloque Actualizado exitosamente.";
                    }
                    else
                    {
                        $this->data['status'] = "error";
                        $this->data['msg'] = "Hubo un error al Actualizar El Bloque, intente nuevamente.";
                    }
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "La hora del Bloque ya existe, ingrese uno nuevo o edite el existente.";
                }
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Faltan parámetros para realizar la operación, intente nuevamente.";
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bloques  $bloques
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->input('id'))
        {
            $atencion = Atencion::where('id_bloque',$request->input('id'))->first();

            if($atencion)
            {
                $this->data['status'] = "error";
                $this->data['msg']    = "No se puede eliminar el bloque ya que existen atenciones hechas para este bloque.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al eliminar el Bloque, intente nuevamente.";
            

                $response = Bloques::where('id_bloque',$request->input('id'))->delete();

                if($response)
                {
                    $this->data['status'] = "success";
                    $this->data['msg'] = "Bloque Eliminado exitosamente.";
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Hubo un error al eliminar el Bloque, intente nuevamente.";
                }
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Faltan parámetros para eliminar el bloque, intente nuevamente.";
        }

        return json_encode($this->data);
    }
}
