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
        $atenciones = DB::table($this->table)->where('id_especialista',$id)->get();
        return $atenciones;
    }

    public function getAtencionesByIdPaciente($id)
    {
        $atenciones = DB::table($this->table)->where('id_paciente',$id)->get();
        return $atenciones;
    }
}
