<?php

namespace App\Http\Controllers;

use App\Mail\NotificarEspecialista;
use App\Mail\PagoPaciente;
use App\Models\Pagos;
use App\Models\Atencion;
use App\Models\Bloques;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Horarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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
        $pago =  Pagos::where('codigo_atencion',$id)->first();
        $atenciones = $this->getInfoAtencion($id);

        if($pago)
        {
            $this->data['atencion'] = $atenciones;
            $this->data['pago'] = $pago;
            return view('pagos.checkoutSuccess',$this->data);
        }
        else
        {
            $this->data['atencion'] = $atenciones;
            $this->data['opcion']   = 1;
            return view('pagos.checkout',$this->data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function successPago(Request $req)
    {
        $atenciones = $this->getInfoAtencion($req->id);

        if(!empty($req->payment_id))
        {
            if($req->status == 'approved' || $req->payment_status == 'approved')
            {
                $atencion = Atencion::where('codigo_atencion',$req->id)->first();

                if(!empty($req->status))
                {
                    $rules = array(
                        'payment_id'        => 'unique:pagos',
                        'merchant_order_id' => 'unique:pagos',
                    );
            
                    $msg = array(
                        'payment_id'        => 'unique:pagos',
                        'merchant_order_id' => 'unique:pagos',
                    );
                }
                else
                {
                    $rules = array(
                        'payment_id'        => 'unique:pagos',
                    );
            
                    $msg = array(
                        'payment_id'        => 'unique:pagos',
                    );
                }
        
                $validador = Validator::make($req->all(), $rules, $msg);

                if ($validador->passes()) 
                {
                    $especialidad = Especialidades::where('id',$atencion->id_especialidad)->first();
                    $especialista = Especialistas::where('id',$atencion->id_especialista)->first();
                    $monto = $especialidad->costo;
                    
                    if(!empty($req->status))
                    {
                        $metodo_pago         = $req->payment_type;
                        $pago_id             = $req->payment_id;
                        $estado_mercado_pago = $req->status;
                        $merchant_order_id   = $req->merchant_order_id;
                    }
                    else
                    {
                        $metodo_pago         = $req->payment_method_id;
                        $pago_id             = $req->payment_id;
                        $estado_mercado_pago = $req->payment_status;
                        $merchant_order_id   = $req->payment_id.'-wp';
                    }

                    $response = Pagos::create([
                        'metodo_pago'           => $metodo_pago,
                        'payment_id'            => $pago_id,
                        'estado_mercado_pago'   => $estado_mercado_pago,
                        'merchant_order_id'     => $merchant_order_id,
                        'id_atencion'           => $atencion->id_atencion,
                        'codigo_atencion'       => $atencion->codigo_atencion,
                        'estado'                => 1,
                        'monto_pago'            => $monto,
                        'fecha_pago'            => date('Y-m-d')
                    ]);

                    if($response)
                    {
                        if($atencion->tipo_atencion == 1){ $tipo_atencion = 'Atención Reservada'; } else { $tipo_atencion = 'Atención Inmediata'; } 

                        $data = array(
                            'codigo'        => $atencion->codigo_atencion,
                            'paciente'      => auth()->user()->name,
                            'especialidad'  => $especialidad->nombre,
                            'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                            'tipo_atencion' => $tipo_atencion,
                            'fecha'         => date('d-m-Y'),
                            'id_pago'       => $pago_id,
                            'id_orden'      => $merchant_order_id,
                            'monto'         => '$'.number_format($monto,0,'.','.'),
                        );

                        if($atencion->tipo_atencion == 1){ $fecha = date("d/m/Y", strtotime($atencion->fecha)); } else { $fecha = date("d/m/Y"); } 

                        $bloque = Bloques::Where('id_bloque',$atencion->id_bloque)->first();
                        
                        if(!empty($bloque)) { $hora_bloq = $bloque->hora_bloque; } else { $hora_bloq = 'No Aplica'; } 
                        
                        $data2 = array(
                            'especialista'  => 'Dr/a '.$especialista->nombres.' '.$especialista->apellido_paterno,
                            'codigo'        => $atencion->codigo_atencion,
                            'rut'           => $atencion->rut_paciente,
                            'paciente'      => auth()->user()->name,
                            'tipo_atencion' => $tipo_atencion,
                            'fecha'         => $fecha,
                            'bloque'        => $hora_bloq,
                        );

                        $espe              = User::where('id',$especialista->id_user)->first();
                        $mail_especialista = $espe->email;  

                        Mail::to(auth()->user()->email)->send(new PagoPaciente($data));
                        Mail::to($mail_especialista)->send(new NotificarEspecialista($data2));

                        $response2 =  Atencion::where('id_atencion',$atencion->id_atencion)->update([ 'estado' =>  2 ]);

                        if($response2)
                        {
                            $pago =  Pagos::where('id_atencion',$atencion->id_atencion)->first();
                            $this->data['atencion'] = $atenciones;
                            $this->data['pago'] = $pago;
                            return view('pagos.checkoutSuccess',$this->data);
                        }
                        else
                        {
                            $this->data['atencion'] = $atenciones;
                            $this->data['opcion'] = 5;
                            return view('pagos.checkout',$this->data);
                        }
                    }
                    else
                    {
                        $this->data['atencion'] = $atenciones;
                        $this->data['opcion'] = 4;
                        return view('pagos.checkout',$this->data);
                    }
                }
                else
                {
                    $pago =  Pagos::where('id_atencion',$atencion->id_atencion)->first();
                    $this->data['atencion'] = $atenciones;
                    $this->data['pago'] = $pago;
                    return view('pagos.checkoutSuccess',$this->data);
                }
            }
            else
            {
                $this->data['atencion'] = $atenciones;
                $this->data['opcion'] = 3;
                return view('pagos.checkout',$this->data);
            }
        }
        else
        {
            $atencion = $this->getInfoAtencion($req->id);
            $this->data['atencion'] = $atenciones;
            $this->data['opcion'] = 2;
            return view('pagos.checkout',$this->data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function show(Pagos $pagos)
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

     /**
     * Get atención.
     *
     * @param  $codigo id
     * @return \Illuminate\Http\Response
     */
    protected function getInfoAtencion($id)
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

        return $atenciones[0];
    }
}
