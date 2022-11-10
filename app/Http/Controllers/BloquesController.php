<?php

namespace App\Http\Controllers;

use App\Models\Bloques;
use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BloquesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $horarios = Horarios::where('id_horario',$id)->first();
        $bloques  = Bloques::where('id_horario',$id)->orderBy('numero_bloque','ASC')->get();

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
            'numero_bloque'  => 'required|unique:bloques|numeric',
            'hora'           => 'required',
            'bloque'         => 'required|max:500',
        ); 

        $msg = array(
            'numero_bloque.required'  => 'El campo Número de Bloque es requerido.',
            'numero_bloque.unique'    => 'El Número de Bloque ya fue ingresado.',
            'numero_bloque.numeric'   => 'El campo Número de Bloque solo acepta valores numéricos.',
            'hora.required'           => 'El campo Hora es requerido.',
            'bloque.required'         => 'El campo Descripción de Bloque es requerido.',
            'bloque.max'              => 'El campo Descripción de Bloque no puede superar los 500 caracteres.',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
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
        dd($request->all());
        $rules = array(
            'numero_bloque'  => 'required|unique:bloques|numeric',
            'hora'           => 'required',
            'bloque'         => 'required|max:500',
        ); 

        $msg = array(
            'numero_bloque.required'  => 'El campo Número de Bloque es requerido.',
            'numero_bloque.unique'    => 'El Número de Bloque ya fue ingresado.',
            'numero_bloque.numeric'   => 'El campo Número de Bloque solo acepta valores numéricos.',
            'hora.required'           => 'El campo Hora es requerido.',
            'bloque.required'         => 'El campo Descripción de Bloque es requerido.',
            'bloque.max'              => 'El campo Descripción de Bloque no puede superar los 500 caracteres.',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $response = Bloques::where('id_bloque',$request->input('id'))->update([
                'numero_bloque' =>  $request->input('numero_bloque'),
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
    public function destroy(Bloques $bloques)
    {
        //
    }
}
