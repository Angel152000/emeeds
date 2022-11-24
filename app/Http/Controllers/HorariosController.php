<?php

namespace App\Http\Controllers;

use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bloques;

class HorariosController extends Controller
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
        $horarios = Horarios::orderBy('dia','ASC')->get();

        $this->data['horarios'] = $horarios;

        return view('horarios.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = array(
            'dia'         => 'required|unique:horarios'
        ); 

        $msg = array(
            'dia.required'     => 'El campo Día es requerido.',
            'dia.unique'       => 'El Día ya fue ingresado.'
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            switch ($request->input('dia')) {
                case 1:
                    $dia_desc = 'Lunes';
                break;
                case 2:
                    $dia_desc = 'Martes';
                break;
                case 3:
                    $dia_desc = 'Miércoles';
                break;
                case 4:
                    $dia_desc = 'Jueves';
                break;
                case 5:
                    $dia_desc = 'Viernes';
                break;
                case 6:
                    $dia_desc = 'Sábado';
                break;
                case 7:
                    $dia_desc = 'Domingo';
                break;
            }

            $response = Horarios::create([
                'dia'   =>  $request->input('dia'),
                'dia_desc' => $dia_desc,
            ]);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Horario Creado exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al Crear El Horario, intente nuevamente.";
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
     * @param  \App\Models\Horarios  $horarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->input('id'))
        {
            $response  = Horarios::where('id_horario',$request->input('id'))->delete();

            if($response)
            {
                Bloques::where('id_horario',$request->input('id'))->delete();

                $this->data['status'] = "success";
                $this->data['msg'] = "Horario Eliminado exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al eliminar el Horario, intente nuevamente.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Faltan parámetros para eliminar el Horario, intente nuevamente.";
        }

        return json_encode($this->data);
    }
}
