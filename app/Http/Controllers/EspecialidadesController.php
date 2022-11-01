<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;
use Validator;

class EspecialidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objEspecialidades = new Especialidades();

        $especialidades = $objEspecialidades->getEspecialidades();

        $this->data['especialidades'] = $especialidades;

        return view('especialidades.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = array(
            'codigo'       => 'required',
            'nombre'       => 'required',
            'descripcion'  => 'required|max:500',
        ); 

        $msg = array(
            'codigo.required'       => 'El campo Código es requerido',
            'nombre.required'       => 'El campo Nombre es requerido',
            'descripcion.required'  => 'El campo Descripción es requerido',
            'descripcion.max'       => 'El campo Descripción no puede superar los 500 caracteres',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $objEspecialidades = new Especialidades();

            $data = array(
                'codigo'      =>  $request->input('codigo'),
                'nombre'      =>  $request->input('nombre'),
                'descripcion' =>  $request->input('descripcion'),
            );

            $response = $objEspecialidades->grabarEspecialidad($data);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Especialidad Creada exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al insertar la Especialidad, intente nuevamente.";
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Especialidades  $especialidades
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $rules = array(
            'codigo'       => 'required',
            'nombre'       => 'required',
            'descripcion'  => 'required|max:500',
        ); 

        $msg = array(
            'codigo.required'       => 'El campo Código es requerido',
            'nombre.required'       => 'El campo Nombre es requerido',
            'descripcion.required'  => 'El campo Descripción es requerido',
            'descripcion.max'       => 'El campo Descripción no puede superar los 500 caracteres',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            $objEspecialidades = new Especialidades();

            $data = array(
                'codigo'      =>  $request->input('codigo'),
                'nombre'      =>  $request->input('nombre'),
                'descripcion' =>  $request->input('descripcion'),
            );

            $response = $objEspecialidades->editarEspecialidad($data);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Especialidad Actualizada exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al Actualizar la Especialidad, intente nuevamente.";
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
     * @param  \App\Models\Especialidades  $especialidades
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $objEspecialidades = new Especialidades();

        $response = $objEspecialidades->eliminarEspecialidad($request->input('id'));

        if($response)
        {
            $this->data['status'] = "success";
            $this->data['msg'] = "Especialidad Eliminada exitosamente.";
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Hubo un error al Eliminar la Especialidad, intente nuevamente.";
        }
        return json_encode($this->data);
    }
}
