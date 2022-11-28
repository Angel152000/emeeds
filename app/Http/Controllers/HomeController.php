<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Atencion;
use App\Models\Fichas;
use App\Models\Horarios;
use App\Models\User;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $objEspecialistas = new Especialistas();
        $objEspecialidades = new Especialidades();
        $objAtencion = new Atencion();
    
        switch (auth()->user()->tipo_user) 
        {
            //Especialista
            case 1:
                $l_especialista = Especialistas::where('id_user',auth()->user()->id)->first();
                $this->data['countAtenciones'] = Atencion::where('id_especialista',$l_especialista->id)->whereIn('estado', [1, 2])->count();
                $this->data['countPaciente']   = Fichas::where('id_especialista',$l_especialista->id)->count();

                $atenciones = $objAtencion->getAtencionesByIdEspecialista($l_especialista->id);
                if(!empty($atenciones))
                {
                    foreach ($atenciones as $row)
                    {
                        $paciente = User::where('id',$row->id_paciente)->first();
                        $row->nombre_paciente = $paciente->name;
                         
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
                $this->data['atenciones'] = $atenciones;

                return view('home', $this->data);
            break;
            //Establecimiento
            case 2:
                $especialistas = $objEspecialistas->getEspecialistas();

                foreach($especialistas as $row)
                {
                    $especialidad = $objEspecialidades->getEspecialidadesById($row->id_especialidad);
                    $row->especialidad = $especialidad->nombre;
                }

                $especialidades = $objEspecialidades->getEspecialidades();

                $this->data['especialidades']     = $especialidades;
                $this->data['especialistas']      = $especialistas;
                $this->data['countHorarios']      = Horarios::count();
                $this->data['countEspecialidad']  = Especialidades::count();
                $this->data['countEspecialistas'] = Especialistas::count();
                $this->data['countAtenciones']    = Atencion::whereIn('estado',[1,2])->count();

                return view('home', $this->data);
            break;
            //Paciente
            case 3:
                $this->data['countAtenciones'] = Atencion::where('id_paciente',auth()->user()->id)->count();
            
                $atenciones = $objAtencion->getAtencionesByIdPaciente(auth()->user()->id);
        
                if(!empty($atenciones))
                {
                    foreach ($atenciones as $row)
                    {
                        $especialidad = Especialidades::where('id',$row->id_especialidad)->first();
                        $row->especialidad = $especialidad->nombre;

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
                $this->data['atenciones'] = $atenciones;
                return view('home', $this->data);
            break;
        }
    }
}
