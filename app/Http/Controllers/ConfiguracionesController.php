<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ConfiguracionesController extends Controller
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
    public function especialista()
    {
        return view('configuracion.especialista');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function establecimiento()
    {
        return view('configuracion.establecimiento');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paciente()
    {
        return view('configuracion.paciente');
    }

    public function cambiarPassword(Request $request)
    {
        $rules = array(
            'clave'          => 'required',
            'rep_clave'      => 'required'
        ); 

        $msg = array(
            'clave.required'          => 'El campo Contraseña Nueva es requerido',
            'rep_clave.required'      => 'El campo Repetir Contraseña es requerido',
        );

        $validador = Validator::make($request->all(), $rules, $msg);

        if ($validador->passes()) 
        {
            if($request->input('clave') != $request->input('rep_clave'))
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Las contraseñas no coinciden, regularice esta situación para poder continuar.";
            }
            else
            {
                $l_user = User::where('id',auth()->user()->id)->update([
                    'password'  => Hash::make($request->input('clave')),
                ]);

                if($l_user)
                {
                    $this->data['status'] = "success";
                    $this->data['msg'] = "Clave modificada exitosamente.";
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Hubo un error al cambiar la clave del usuario, intente nuevamente.";
                }
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
