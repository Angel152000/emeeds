<?php

namespace App\Http\Controllers;

use App\Models\Especialistas;
use App\Models\Especialidades;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EspecialistasController extends Controller
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
        $objEspecialistas = new Especialistas();
        $objEspecialidades = new Especialidades();

        $especialistas = $objEspecialistas->getEspecialistas();

        foreach($especialistas as $row)
        {
            $especialidad = $objEspecialidades->getEspecialidadesById($row->id_especialidad);
            $row->especialidad = $especialidad->nombre;
        }

        $this->data['especialistas'] = $especialistas;

        return view('especialistas.index', $this->data);
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

        return view('especialistas.create',$this->data);
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
            'rut'            => 'required|max:15|unique:especialistas',
            'nombres'        => 'required|max:300',
            'apellidop'      => 'required|max:255',
            'apellidom'      => 'required|max:255',
            'fecha_nac'      => 'required',
            'sexo'           => 'required',
            'email'          => 'required|email|max:500|unique:users',
            'telefono'       => 'required|max:9',
            'especialidad'   => 'required',
            'tipo_atencion'  => 'required',
            'clave'          => 'required',
            'rep_clave'      => 'required',
        ); 

        $msg = array(
            'rut.required'            => 'El campo Rut es requerido',
            'nombres.required'        => 'El campo Nombres es requerido',
            'apellidop.required'      => 'El campo Apellido Paterno es requerido',
            'apellidom.required'      => 'El campo Apellido Materno es requerido',
            'fecha_nac.required'      => 'El campo Fecha de Nacimiento es requerido',
            'sexo.required'           => 'El campo Sexo es requerido',
            'email.required'          => 'El campo Email es requerido',
            'telefono.required'       => 'El campo Teléfono es requerido',
            'especialidad.required'   => 'El campo Especialidad es requerido',
            'tipo_atencion.required'  => 'El campo Tipo de Atención es requerido',
            'clave.required'          => 'El campo Contraseña es requerido',
            'rep_clave.required'      => 'El campo Repetir Contraseña es requerido',
            'rut.max'                 => 'El campo Rut no puede superar los 15 caracteres',
            'telefono.max'            => 'El campo Teléfono no puede superar los 9 digitos',
            'nombres.max'             => 'El campo Nombres no puede superar los 300 caracteres',
            'apellidop.max'           => 'El campo Apellido Paterno no puede superar los 255 caracteres',
            'apellidom.max'           => 'El campo Apellido Materno no puede superar los 255 caracteres',
            'email.max'               => 'El campo Email no puede superar los 500 caracteres',
            'email.unique'            => 'El Email ya existe en el sistema, porfavor ingrese otro',
            'rut.unique'              => 'El Rut ya existe en el sistema, porfavor ingrese otro',
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
                $l_user = User::create([
                    'name'      => $request->input('nombres').' '.$request->input('apellidop'),
                    'email'     => $request->input('email'),
                    'password'  => Hash::make($request->input('clave')),
                    'tipo_user' => 1,
                ]);

                if($l_user)
                {
                    $objEspecialistas = new Especialistas();

                    $data = array(
                        'id_especialidad'  => $request->input('especialidad'),
                        'tipo_atencion'    => $request->input('tipo_atencion'),
                        'id_user'          => $l_user->id,
                        'rut'              => $request->input('rut'),
                        'nombres'          => $request->input('nombres'),
                        'apellido_paterno' => $request->input('apellidop'),
                        'apellido_materno' => $request->input('apellidom'),
                        'fecha_nacimiento' => $request->input('fecha_nac'),
                        'sexo'             => $request->input('sexo'),
                        'email'            => $request->input('email'),
                        'telefono'         => $request->input('telefono'),
                        'created_at'       => date('Y-m-d h:m:s')
                    );
        
                    $response = $objEspecialistas->grabarEspecialistas($data);
        
                    if($response)
                    {
                        $this->data['status'] = "success";
                        $this->data['msg'] = "Especialista Ingresado exitosamente.";
                    }
                    else
                    {
                        $this->data['status'] = "error";
                        $this->data['msg'] = "Hubo un error al insertar el Especialista, intente nuevamente.";
                    }
                }
                else
                {
                    $this->data['status'] = "error";
                    $this->data['msg'] = 'No se pudo crear la cuenta del usuario, Intente nuevamente';
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Especialistas  $especialistas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $objEspecialistas = new Especialistas();
        $objEspecialidades = new Especialidades();

        $especialistas = $objEspecialistas->getEspecialistasById($id);
        $especialidades = $objEspecialidades->getEspecialidades();

        $this->data['especialidades'] = $especialidades;
        $this->data['especialista'] = $especialistas;

        return view('especialistas.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Especialistas  $especialistas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { 
        $objEspecialistas = new Especialistas();
        $especialistas = $objEspecialistas->getEspecialistasById($request->input('id'));

        if(!$especialistas)
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Hubo un error al encontrar el Especialista, intente nuevamente.";
        }
        else
        {
            if($especialistas->rut != $request->input('rut'))
            {
                $unique_e = '|unique:especialistas';
            }
            else
            {
                $unique_e = '';
            }
        
            $rules = array(
                'rut'            => 'required|max:15'.$unique_e.'',
                'nombres'        => 'required|max:300',
                'apellidop'      => 'required|max:255',
                'apellidom'      => 'required|max:255',
                'fecha_nac'      => 'required',
                'sexo'           => 'required',
                'telefono'       => 'required|max:9',
                'especialidad'   => 'required',
                'tipo_atencion'  => 'required',
            ); 

            $msg = array(
                'rut.required'            => 'El campo Rut es requerido',
                'nombres.required'        => 'El campo Nombres es requerido',
                'apellidop.required'      => 'El campo Apellido Paterno es requerido',
                'apellidom.required'      => 'El campo Apellido Materno es requerido',
                'fecha_nac.required'      => 'El campo Fecha de Nacimiento es requerido',
                'sexo.required'           => 'El campo Sexo es requerido',
                'telefono.required'       => 'El campo Teléfono es requerido',
                'especialidad.required'   => 'El campo Especialidad es requerido',
                'tipo_atencion.required'  => 'El campo Tipo de Atención es requerido',
                'rut.max'                 => 'El campo Rut no puede superar los 15 caracteres',
                'telefono.max'            => 'El campo Teléfono no puede superar los 9 digitos',
                'nombres.max'             => 'El campo Nombres no puede superar los 300 caracteres',
                'apellidop.max'           => 'El campo Apellido Paterno no puede superar los 255 caracteres',
                'apellidom.max'           => 'El campo Apellido Materno no puede superar los 255 caracteres',
                'rut.unique'              => 'El Rut ya existe en el sistema, porfavor ingrese otro',
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
                    $data = array(
                        'id_especialidad'  => $request->input('especialidad'),
                        'tipo_atencion'    => $request->input('tipo_atencion'),
                        'rut'              => $request->input('rut'),
                        'nombres'          => $request->input('nombres'),
                        'apellido_paterno' => $request->input('apellidop'),
                        'apellido_materno' => $request->input('apellidom'),
                        'fecha_nacimiento' => $request->input('fecha_nac'),
                        'sexo'             => $request->input('sexo'),
                        'telefono'         => $request->input('telefono'),
                        'updated_at'       => date('Y-m-d h:m:s')
                    );
            
                    $response = $objEspecialistas->editarEspecialistas($data,$request->input('id'));
        
                    if($response)
                    {
                        $this->data['status'] = "success";
                        $this->data['msg'] = "Especialista Actualizado exitosamente.";
                    }
                    else
                    {
                        $this->data['status'] = "error";
                        $this->data['msg'] = "Hubo un error al actualizar el Especialista, intente nuevamente.";
                    }
                }
            }
            else 
            {
                $this->data['status'] = "error";
                $this->data['msg'] = $validador->errors()->first();
            }
        }

        return json_encode($this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Especialistas  $especialistas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $objEspecialistas = new Especialistas();
        $especialistas = $objEspecialistas->getEspecialistasById($request->input('id'));
        $deleteEspe    = $objEspecialistas->eliminarEspecialistas($request->input('id'));

        if($deleteEspe)
        {
            $user = User::where('id',$especialistas->id_user)->delete();
            if($user) 
            {
                $this->data['status'] = "success";
                $this->data['msg'] = "Especialista Eliminado exitosamente.";
            }
            else
            {
                $this->data['status'] = "error";
                $this->data['msg'] = "Hubo un error al eliminar al Especialista del Sistema, intente nuevamente.";
            }
        }
        else
        {
            $this->data['status'] = "error";
            $this->data['msg'] = "Hubo un error al eliminar al Especialista, intente nuevamente.";
        }

        return json_encode($this->data);
    }
}
