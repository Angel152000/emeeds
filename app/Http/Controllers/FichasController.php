<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Fichas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FichasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objFichas = new Fichas();
        $especialista = Especialistas::where('id_user',auth()->user()->id)->first();
        $fichas = $objFichas->getFichasByEspecialista($especialista->id);

        foreach ($fichas as $row)
        {
            $paciente      = User::where('id',$row->id_paciente)->first();
            $row->paciente = $paciente->name;
        }

        $this->data['pacientes'] = $fichas;

        return view('ficha.index',$this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paciente()
    {
        $objFichas    = new Fichas();
        $fichas       = $objFichas->getFichasByPaciente(auth()->user()->id);

        foreach ($fichas as $row)
        {
            $atencion     = Atencion::where('id_atencion',$row->id_atencion)->first();
            $especialista = Especialistas::where('id',$atencion->id_especialista)->first();
            $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();
            $row->especialidad = $especialidad->nombre;

            $row->especialista = 'Dr/a '.$especialista->nombres.' '. $especialista->apellido_paterno;

            switch ($atencion->tipo_atencion) 
            {
                case 1:
                    $row->atencion = 'Atención Reservada';
                    $row->fecha_at = date("d/m/Y", strtotime($atencion->fecha));
                break;
                case 2:
                    $row->atencion = 'Atención Inmediata';
                    $row->fecha_at = 'No aplica.';
                break;
                case 3:
                    $row->atencion = 'Sin tipo de atención';
                    $row->fecha_at = 'No aplica.';
                break;
            }

        }

        $this->data['fichas'] = $fichas;

        return view('ficha.ficha',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $objAtenciones = new Atencion();
        $atenciones = $objAtenciones->getAtencionesByIdPacienteEs($id);
    
        foreach ($atenciones as $row)
        {
            $especialista = Especialistas::where('id',$row->id_especialista)->first();
            $row->especialista = 'Dr/a '.$especialista->nombres.' '. $especialista->apellido_paterno;

            $especialidad = Especialidades::where('id',$row->id_especialidad)->first();
            $row->especialidad = $especialidad->nombre;

            $ficha = Fichas::where('id_atencion',$row->id_atencion)->first();
            $row->notas = $ficha->nota;
            $row->id_ficha = $ficha->id_ficha;
                
            switch ($row->tipo_atencion) 
            {
                case 1:
                    $row->atencion = 'Atención Reservada';
                    $row->fecha_at = date("d/m/Y", strtotime($row->fecha));
                break;
                case 2:
                    $row->atencion = 'Atención Inmediata';
                    $row->fecha_at = 'No aplica.';
                break;
                case 3:
                    $row->atencion = 'Sin tipo de atención';
                    $row->fecha_at = 'No aplica.';
                break;
            }            
        }

        $paciente = User::where('id',$id)->first();
        $espe     = Especialistas::where('id_user',auth()->user()->id)->first();

        $this->data['atenciones']      = $atenciones;
        $this->data['paciente']        = $paciente->name;
        $this->data['id_especialista'] = $espe->id;
        return view('ficha.paciente',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fichas  $fichas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'nota' => 'required',
        ); 

        $msg = array(
            'nota.required' => 'El campo Nota es requerido',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {

            $response = Fichas::where('id_atencion',$request->input('id'))->update([
                'nota' => $request->input('nota'),
            ]);

            if($response)
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Nota Actualizada exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al Actualizar la Nota, intente nuevamente.";
            }
        }
        else 
        {
            $this->data['status'] = "error";
            $this->data['msg'] = $validador->errors()->first();
        } 

        return json_encode($this->data);
    }
}
