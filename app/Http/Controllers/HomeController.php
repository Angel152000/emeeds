<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidades;
use App\Models\Especialistas;
use App\Models\Atencion;
use App\Models\Horarios;

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

        return view('home', $this->data);
    }
}
