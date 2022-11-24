<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Atencion;
use App\Models\Bloques;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Horarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagosController extends Controller
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
        $objAtencion = new Atencion();

        $atenciones = $objAtencion->getAtencionesByCodigo($id);
        if(!empty($atenciones))
        {
            foreach ($atenciones as $row)
            {
                $especialidad = Especialidades::where('id',$row->id_especialidad)->first();
                $row->especialidad = $especialidad->nombre;
                $row->costo = $especialidad->costo;

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
        $this->data['atencion'] = $atenciones[0];
        return view('pagos.checkout',$this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function successPago(Request $req)
    {
        dd($req);
        return view('pagos.checkout',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function edit(Pagos $pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagos $pagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagos $pagos)
    {
        //
    }
}
