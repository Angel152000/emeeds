<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Atencion extends Model
{
    use HasFactory;
    protected $table = 'atenciones';

    protected $fillable = [
        'id_atencion',
        'codigo_atencion',
        'tipo_atencion',
        'id_especialidad',
        'id_especialista',
        'id_paciente',
        'rut_paciente',
        'nombre_paciente',
        'detalle_atencion',
        'id_horario',
        'id_bloque',
        'fecha',
        'estado'
    ];

    public function getAtenciones()
    {
        $atenciones = DB::table($this->table)->get();
        return $atenciones;
    }

    public function getAtencionesByIdEspecialista($id)
    {
        $atenciones = DB::table($this->table)->where('id_especialista',$id)->whereIn('estado', [1, 2, 5])->get();
        return $atenciones;
    }

    public function getAtencionesByIdPaciente($id)
    {
        $atenciones = DB::table($this->table)->where('id_paciente',$id)->get();
        return $atenciones;
    }

    public function getAtencionesByIdPacienteEs($id)
    {
        $atenciones = DB::table($this->table)->where('id_paciente',$id)->whereIn('estado',[1,2])->get();
        return $atenciones;
    }

    public function getAtencionesByCodigo($id)
    {
        $atenciones = DB::table($this->table)->where('codigo_atencion',$id)->get();
        return $atenciones;
    }

    public function getAtencionesByAdmin()
    {
        $atenciones = DB::table($this->table)->whereIn('estado',[1,2,5])->get();
        return $atenciones;
    }
}
