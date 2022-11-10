<?php

namespace App\Http\Controllers;

use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorariosController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Horarios  $horarios
     * @return \Illuminate\Http\Response
     */
    public function show(Horarios $horarios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Horarios  $horarios
     * @return \Illuminate\Http\Response
     */
    public function edit(Horarios $horarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Horarios  $horarios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horarios $horarios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Horarios  $horarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horarios $horarios)
    {
        //
    }
}
